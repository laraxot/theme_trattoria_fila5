"""Prova end-to-end dei flussi Restaurant (ristoratore, cliente al tavolo, header).

Uso: python3 flows.py <cartella-screenshot>
"""
import os
import json
import re
import subprocess
import sys
from pathlib import Path

from playwright.sync_api import sync_playwright, expect

BASE = os.environ.get('RESTAURANT_BASE_URL', 'http://restaurant.local:8000')
SLUG = 'trattoria-da-mario'
PANEL = f'{BASE}/restaurant/restaurateur'
OUT = Path(sys.argv[1] if len(sys.argv) > 1 else 'shots')
OUT.mkdir(parents=True, exist_ok=True)
results = []


def check(name, ok, detail=''):
    results.append((name, bool(ok), detail))
    print(('OK   ' if ok else 'FAIL ') + name + (f' -- {detail}' if detail else ''), flush=True)


def sql(query):
    return subprocess.run(['mysql', '-N', '-e', query], capture_output=True, text=True).stdout.strip()


def shot(page, name, full=True):
    page.screenshot(path=str(OUT / f'{name}.png'), full_page=full)


def no_error(page):
    text = page.content()
    return not re.search(r'Server Error|SERVER ERROR|Whoops|ErrorException|ParseError|>500<', text)


with sync_playwright() as p:
    browser = p.chromium.launch()

    # ---------------------------------------------------------------- ospite: header con "Accedi"
    ctx = browser.new_context(viewport={'width': 1280, 'height': 860}, locale='it-IT')
    page = ctx.new_page()
    page.goto(f'{BASE}/it/restaurants/{SLUG}')
    check('minisito risponde', no_error(page))
    check('header ospite: link Accedi', page.locator('a.user-login').count() == 1)
    shot(page, 'header-ospite', full=False)
    ctx.close()

    # ---------------------------------------------------------------- ristoratore
    ctx = browser.new_context(viewport={'width': 1366, 'height': 900}, locale='it-IT')
    page = ctx.new_page()
    page.goto(f'{PANEL}/login')
    page.locator('input[type="email"]').fill('ristoratore@example.test')
    page.locator('input[type="password"]').fill('password')
    page.locator('button[type="submit"]').click()
    page.wait_for_load_state('networkidle')
    check('login ristoratore', '/login' not in page.url, page.url)

    area = f'{PANEL}/{SLUG}'
    page.goto(f'{area}/dining-tables')
    page.wait_for_load_state('networkidle')
    check('lista tavoli', no_error(page) and page.locator('text=/Stampa tutti i QR|Print all QR/').count() > 0)
    shot(page, 'tavoli-lista', full=False)

    # nuovo tavolo (pulizia dei resti di esecuzioni precedenti)
    sql("delete from restaurant_data.tables_map where name='Terrazza 1'")
    page.goto(f'{area}/dining-tables/create')
    page.wait_for_load_state('networkidle')
    page.locator('input[id$="name"]').first.fill('Terrazza 1')
    page.locator('input[id$="seats"]').first.fill('2')
    shot(page, 'tavolo-nuovo', full=False)
    page.get_by_role('button', name=re.compile(r"^(Crea|Salva)$")).first.click()
    page.wait_for_url(lambda url: '/create' not in url, timeout=15000)
    page.wait_for_load_state('networkidle')
    tid = sql(f"select tenant_id from restaurant_data.tables_map where name='Terrazza 1'")
    mario_id = sql(f"select id from restaurant_user.tenants where slug='{SLUG}'")
    check('tavolo creato nel ristorante giusto', tid == mario_id, f'{tid} vs {mario_id}')

    # nome duplicato rifiutato
    page.goto(f'{area}/dining-tables/create')
    page.wait_for_load_state('networkidle')
    page.locator('input[id$="name"]').first.fill('Terrazza 1')
    page.get_by_role('button', name=re.compile(r"^(Crea|Salva)$")).first.click()
    page.wait_for_timeout(1500)
    shot(page, 'tavolo-nome-duplicato', full=False)
    check('nome tavolo duplicato rifiutato', sql("select count(*) from restaurant_data.tables_map where name='Terrazza 1'") == '1')

    # stampa: tre modelli
    for template in ('tent', 'sticker', 'poster'):
        page.goto(f'{area}/print-table-qr?template={template}')
        page.wait_for_load_state('networkidle')
        cards = page.locator('.qrp-page').count()
        check(f'stampa {template}', no_error(page) and cards > 0 and page.locator('.qrp-qr svg').count() > 0, f'{cards} pagine')
        shot(page, f'stampa-{template}')
        page.emulate_media(media='print')
        shot(page, f'stampa-{template}-anteprima')
        hidden = page.locator('.fi-sidebar').count() == 0 or not page.locator('.fi-sidebar').first.is_visible()
        check(f'stampa {template}: menu del pannello nascosto', hidden)
        page.emulate_media(media='screen')
    page.goto(f'{area}/print-table-qr?template=poster')
    page.pdf(path=str(OUT / 'stampa-poster.pdf'), format='A4', print_background=True) if False else None

    # interruttore ordini: stato iniziale (seeder demo: acceso)
    page.goto(f'{area}/dining-tables')
    page.wait_for_load_state('networkidle')
    toggle = page.get_by_role('button', name=re.compile('Ordini dal tavolo'))
    check('interruttore ordini visibile', toggle.count() > 0, toggle.first.inner_text() if toggle.count() else '')

    # QR del tavolo 5: link firmato
    row = page.locator('tr', has_text=re.compile(r'^\s*5\s')).first
    page.get_by_role('button', name='QR del tavolo').first.click()
    page.locator('.fi-modal input[readonly]').first.wait_for()
    table_url = page.locator('.fi-modal input[readonly]').first.input_value()
    check('link firmato del tavolo', 'signature=' in table_url and '/table/' in table_url, table_url[:90])
    shot(page, 'tavolo-qr-modale', full=False)
    page.keyboard.press('Escape')

    # ---------------------------------------------------------------- cliente al tavolo (ordini attivi)
    cctx = browser.new_context(viewport={'width': 390, 'height': 844}, device_scale_factor=2, is_mobile=True, has_touch=True, locale='it-IT')
    cpage = cctx.new_page()
    cpage.goto(table_url)
    cpage.wait_for_load_state('networkidle')
    check('cliente: pagina ordine dal tavolo', no_error(cpage) and '/menu/' not in cpage.url, cpage.url[:80])
    shot(cpage, 'cliente-ordine-tavolo')
    plus = cpage.locator('button[wire\\:click^="inc"]').first
    before = sql('select count(*) from restaurant_data.orders where source="customer_qr"')
    if plus.count():
        plus.click()
        cpage.wait_for_timeout(700)
        cpage.locator('button[wire\\:click="submit"], button[type="submit"]').last.click()
        cpage.wait_for_timeout(1500)
        after = sql('select count(*) from restaurant_data.orders where source="customer_qr"')
        check('cliente: ordine creato', int(after or 0) == int(before or 0) + 1, f'{before} -> {after}')
        table_id = re.search(r'/table/(\d+)/order', table_url).group(1)
        last = sql('select table_id from restaurant_data.orders where source="customer_qr" order by id desc limit 1')
        check('ordine sul tavolo del QR', last == table_id, f'{last} vs {table_id}')
        items = sql(f'select count(*) from restaurant_data.order_items oi join restaurant_data.orders o on o.id=oi.order_id where o.table_id={table_id}')
        check('righe ordine salvate', int(items or 0) >= 1, items)
        shot(cpage, 'cliente-ordine-inviato')
    else:
        check('cliente: pulsante + sui piatti', False)

    # link manomesso -> 404
    tampered = re.sub(r'signature=[0-9a-f]+', 'signature=deadbeef', table_url)
    r = cpage.goto(tampered)
    check('link manomesso: 404', r is not None and r.status == 404, str(r.status if r else None))

    # ---------------------------------------------------------------- ordini spenti -> il QR apre il menu
    page.goto(f'{area}/dining-tables')
    page.wait_for_load_state('networkidle')
    page.get_by_role('button', name=re.compile('Ordini dal tavolo: attivi')).first.click()
    page.get_by_role('button', name=re.compile('^Conferma$')).first.click()
    page.wait_for_timeout(1000)
    setting = sql(f"select settings from restaurant_user.tenants where slug='{SLUG}'")
    check('ordini spenti salvati', json.loads(setting).get('table_ordering') is False, setting[-60:])
    cpage.goto(table_url)
    cpage.wait_for_load_state('networkidle')
    check('ordini spenti: il QR apre il menu', f'/menu/{SLUG}' in cpage.url, cpage.url)
    shot(cpage, 'cliente-qr-solo-menu')
    # riaccende per lasciare la demo come prima
    page.get_by_role('button', name=re.compile('Ordini dal tavolo: spenti')).first.click()
    page.get_by_role('button', name=re.compile('^Conferma$')).first.click()
    page.wait_for_timeout(800)

    # ---------------------------------------------------------------- header da loggato (ristoratore)
    page.goto(f'{BASE}/it/restaurants/{SLUG}')
    page.wait_for_load_state('networkidle')
    check('header loggato: avatar', page.locator('.user-menu__avatar').count() == 1)
    page.locator('.user-menu__toggle').click()
    check('menu avatar: Il mio ristorante', page.locator('.user-menu__item', has_text='Il mio ristorante').count() == 1)
    shot(page, 'header-avatar-ristoratore', full=False)
    page.locator('.user-menu__item--button').click()
    page.wait_for_load_state('networkidle')
    page.goto(f'{BASE}/it/restaurants/{SLUG}')
    check('logout dal menu avatar', page.locator('a.user-login').count() == 1)
    ctx.close()

    # ---------------------------------------------------------------- cliente loggato dal front office
    ctx = browser.new_context(viewport={'width': 390, 'height': 844}, device_scale_factor=2, is_mobile=True, has_touch=True, locale='it-IT')
    page = ctx.new_page()
    page.goto(f'{BASE}/it/auth/login')
    page.wait_for_load_state('networkidle')
    check('pagina login front office', no_error(page))
    shot(page, 'login-front-office', full=False)
    page.locator('#email').fill('cliente@example.test')
    page.locator('#password').fill('password')
    page.locator('form button[type="submit"]').first.click()
    page.wait_for_url(lambda url: '/auth/login' not in url, timeout=15000)
    page.wait_for_load_state('networkidle')
    page.goto(f'{BASE}/it/menu/{SLUG}')
    page.wait_for_load_state('networkidle')
    has_avatar = page.locator('.user-menu__avatar').count() == 1
    check('cliente loggato: avatar nell header', has_avatar)
    if has_avatar:
        page.locator('.user-menu__toggle').click()
        check('cliente: nessuna area di lavoro', page.locator('.user-menu__heading').count() == 0)
        shot(page, 'header-avatar-cliente', full=False)
    ctx.close()

    browser.close()

failed = [r for r in results if not r[1]]
print(f'\n{len(results) - len(failed)}/{len(results)} verifiche superate')
sys.exit(1 if failed else 0)
