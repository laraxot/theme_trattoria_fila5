"""Audit integrale del front office Restaurant: ospite e loggato, desktop e telefono, it/en.

Per ogni pagina: HTTP, errori console, richieste fallite, chiavi di traduzione grezze, overflow
orizzontale su telefono, immagini rotte o senza alt, titolo, h1, link interni rotti, axe-core.
Uso: python3 audit.py <cartella-output>
"""
import os
import json
import re
import sys
import urllib.request
from collections import defaultdict
from pathlib import Path
from urllib.parse import urljoin, urlparse

from playwright.sync_api import sync_playwright

BASE = os.environ.get('RESTAURANT_BASE_URL', 'http://restaurant.local:8000')
OUT = Path(sys.argv[1] if len(sys.argv) > 1 else 'audit')
(OUT / 'shots').mkdir(parents=True, exist_ok=True)
AXE = Path(__file__).with_name('node_modules').joinpath('axe-core/axe.min.js').read_text()
SLUG = 'trattoria-da-mario'

SEEDS = [
    '/', '/it', '/en',
    '/it/menu', f'/it/menu/{SLUG}', f'/it/menu/{SLUG}?senza[]=gluten&senza[]=milk', f'/it/menu/{SLUG}?dieta[]=vegan',
    '/it/restaurants', f'/it/restaurants/{SLUG}', f'/it/restaurants/{SLUG}/menu', f'/it/restaurants/{SLUG}/book',
    '/it/prenota', '/it/ordine', '/it/chi-siamo', '/it/about', '/it/contatti', '/it/contacts', '/it/faq', '/it/faqs',
    '/it/privacy', '/it/cookie', '/it/cookies', '/it/terms', '/en/privacy', '/en/faq', '/en/contatti',
    '/it/auth/login', '/it/auth/register', '/it/auth/password/reset', '/it/table/1/qr', '/it/pagina-che-non-esiste',
    '/en/menu', f'/en/menu/{SLUG}', f'/en/restaurants/{SLUG}', '/en/auth/login',
]
KEY_RE = re.compile(r'\b(?:pub_theme|restaurant|xot|user|cms|ui|lang)::[a-z0-9_.-]+', re.I)
RAW_RE = re.compile(r'\b(?:fields|actions|label|placeholder|helper_text)\.[a-z_]+\b')


def fetch_status(url):
    try:
        req = urllib.request.Request(url, method='GET', headers={'User-Agent': 'audit'})
        with urllib.request.urlopen(req, timeout=20) as r:
            return r.status
    except urllib.error.HTTPError as e:
        return e.code
    except Exception as e:  # noqa: BLE001
        return f'ERR {e}'


def login(page, email):
    page.goto(f'{BASE}/it/auth/login')
    page.fill('#email', email)
    page.fill('#password', 'password')
    page.click('form button[type=submit]')
    page.wait_for_url(lambda u: '/auth/login' not in u, timeout=15000)


def audit_page(page, url, tag, mobile):
    issues = []
    console, failed = [], []
    page.on('console', lambda m: console.append(m.text) if m.type == 'error' else None)
    page.on('requestfailed', lambda r: failed.append(f'{r.url} ({r.failure})'))
    page.on('response', lambda r: failed.append(f'{r.status} {r.url}') if r.status >= 400 and urlparse(r.url).netloc == urlparse(BASE).netloc and r.url.rstrip('/') != (BASE + url).rstrip('/') else None)
    try:
        resp = page.goto(BASE + url, wait_until='networkidle', timeout=30000)
    except Exception as e:  # noqa: BLE001
        return {'url': url, 'tag': tag, 'status': 'TIMEOUT', 'issues': [str(e)[:200]], 'links': []}
    status = resp.status if resp else None
    final = page.url
    text = page.inner_text('body') if page.locator('body').count() else ''
    html = page.content()
    if status and status >= 500:
        issues.append(f'HTTP {status}')
    if re.search(r'Whoops|ErrorException|Server Error|ParseError|Undefined (variable|array key)', html):
        issues.append('pagina di errore PHP')
    keys = sorted(set(KEY_RE.findall(text)))
    if keys:
        issues.append('chiavi non tradotte: ' + ', '.join(keys[:8]))
    raw = sorted(set(RAW_RE.findall(text)))
    if raw:
        issues.append('testo grezzo: ' + ', '.join(raw[:8]))
    if console:
        issues.append('console: ' + ' | '.join(c[:140] for c in console[:4]))
    if failed:
        issues.append('richieste fallite: ' + ' | '.join(f[:140] for f in sorted(set(failed))[:6]))
    info = page.evaluate("""() => ({
        title: document.title,
        lang: document.documentElement.lang,
        h1: [...document.querySelectorAll('h1')].map(h => h.innerText.trim()).slice(0, 3),
        overflow: document.documentElement.scrollWidth - window.innerWidth,
        wide: [...document.querySelectorAll('body *')].filter(e => { const r = e.getBoundingClientRect(); return r.right > window.innerWidth + 2 && r.width > 0 && getComputedStyle(e).position !== 'fixed'; }).slice(0, 4).map(e => e.tagName.toLowerCase() + (e.className && typeof e.className === 'string' ? '.' + e.className.split(' ')[0] : '')),
        brokenImg: [...document.images].filter(i => i.complete && i.naturalWidth === 0).map(i => i.getAttribute('src')).slice(0, 5),
        noAlt: [...document.images].filter(i => !i.hasAttribute('alt')).map(i => i.getAttribute('src')).slice(0, 5),
        links: [...document.querySelectorAll('a[href]')].map(a => a.href),
        emptyLinks: [...document.querySelectorAll('a, button')].filter(a => a.checkVisibility({visibilityProperty: true})).filter(a => !a.innerText.trim() && !a.getAttribute('aria-label') && !a.querySelector('img[alt]') && !a.getAttribute('title')).length,
    })""")
    if not info['title'].strip():
        issues.append('titolo vuoto')
    if len(info['h1']) != 1 and status == 200:
        issues.append(f"h1: {len(info['h1'])} {info['h1']}")
    if info['lang'] not in ('it', 'en', 'it-IT', 'en-US', 'en-GB'):
        issues.append(f"lang html: {info['lang']!r}")
    if mobile and info['overflow'] > 2:
        issues.append(f"overflow orizzontale {info['overflow']}px: {info['wide']}")
    if info['brokenImg']:
        issues.append('immagini rotte: ' + ', '.join(info['brokenImg']))
    if info['noAlt']:
        issues.append('img senza alt: ' + ', '.join(str(x) for x in info['noAlt']))
    if info['emptyLinks']:
        issues.append(f"link/pulsanti visibili senza testo accessibile: {info['emptyLinks']}")
    try:
        page.add_script_tag(content=AXE)
        axe = page.evaluate("async () => (await axe.run(document, {runOnly: ['wcag2a','wcag2aa']})).violations.map(v => ({id: v.id, impact: v.impact, n: v.nodes.length, t: v.nodes[0] && v.nodes[0].target.join(' ')}))")
        for v in axe:
            if v['impact'] in ('serious', 'critical'):
                issues.append(f"a11y {v['impact']} {v['id']} x{v['n']} ({v['t'][:60]})")
    except Exception as e:  # noqa: BLE001
        issues.append(f'axe non eseguito: {str(e)[:80]}')
    name = re.sub(r'[^a-z0-9]+', '-', (tag + url).lower()).strip('-')[:90]
    page.screenshot(path=str(OUT / 'shots' / f'{name}.png'), full_page=False)
    page.remove_listener('console', page.listeners('console')[0]) if hasattr(page, 'listeners') else None
    return {'url': url, 'final': final.replace(BASE, ''), 'tag': tag, 'status': status, 'issues': issues, 'links': info['links'], 'shot': name}


def run(p, tag, mobile, email=None):
    browser = p.chromium.launch()
    kw = {'viewport': {'width': 390, 'height': 844}, 'is_mobile': True, 'has_touch': True} if mobile else {'viewport': {'width': 1366, 'height': 900}}
    ctx = browser.new_context(**kw, locale='it-IT')
    page = ctx.new_page()
    if email:
        login(page, email)
    rows = []
    for url in SEEDS:
        pg = ctx.new_page()
        rows.append(audit_page(pg, url, tag, mobile))
        pg.close()
        print(f"{tag:18} {str(rows[-1]['status']):>4} {url:55} {len(rows[-1]['issues'])} problemi", flush=True)
    browser.close()
    return rows


with sync_playwright() as p:
    all_rows = []
    for tag, mobile, email in [
        ('ospite-desktop', False, None),
        ('ospite-telefono', True, None),
        ('cliente-telefono', True, 'cliente@example.test'),
        ('ristoratore-desk', False, 'ristoratore@example.test'),
    ]:
        all_rows += run(p, tag, mobile, email)

# link interni rotti (una volta per URL)
links = sorted({l.split('#')[0] for r in all_rows for l in r['links'] if urlparse(l).netloc == urlparse(BASE).netloc and not l.endswith(('logout',))})
broken = {}
for l in links:
    s = fetch_status(l)
    if not isinstance(s, int) or s >= 400:
        broken[l.replace(BASE, '')] = s
for r in all_rows:
    r.pop('links', None)
json.dump({'rows': all_rows, 'broken_links': broken}, open(OUT / 'audit.json', 'w'), indent=1, ensure_ascii=False)

agg = defaultdict(set)
for r in all_rows:
    for i in r['issues']:
        agg[re.sub(r'\d+px', 'Npx', i)[:170]].add(r['url'])
print('\n=== PROBLEMI (pagine coinvolte) ===')
for i, urls in sorted(agg.items(), key=lambda kv: -len(kv[1])):
    print(f'[{len(urls):2}] {i}  <- {sorted(urls)[:4]}')
print('\n=== LINK INTERNI ROTTI ===')
for l, s in broken.items():
    print(s, l)
