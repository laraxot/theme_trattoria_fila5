"""E2E: password dimenticata -> link nel log -> nuova password -> accesso. Ripristina 'password'."""
import os
import re, subprocess, time
from pathlib import Path
from playwright.sync_api import sync_playwright
BASE = os.environ.get('RESTAURANT_BASE_URL', 'http://restaurant.local:8000')
# Log delle email (MAIL_MAILER=log). Default: da laravel/Themes/Trattoria/tests/e2e a laravel/storage/logs.
LOG = Path(os.environ.get('RESTAURANT_LOG', Path(__file__).resolve().parents[4] / 'storage/logs/laravel.log'))
EMAIL='cliente@example.test'
def reset(page, new):
    start=LOG.stat().st_size if LOG.exists() else 0
    page.goto(f'{BASE}/it/auth/password/reset'); page.fill('#email', EMAIL); page.click('form button[type=submit]')
    try:
        page.wait_for_selector('.alert--success', timeout=15000)
    except Exception:
        print(page.inner_text('main')[:600]); raise
    time.sleep(1)
    txt=LOG.read_text(errors='ignore')[start:]
    m=re.findall(re.escape(BASE) + r'(/[a-z]{2}/auth/password/[a-f0-9]{40,})', txt.replace('=\n','').replace('=3D','='))
    assert m, 'link non trovato nel log:\n'+txt[-1500:]
    link=BASE+m[-1]; print('link', link[:90])
    page.goto(link)
    if not page.input_value('#email'): page.fill('#email', EMAIL)
    page.fill('#password', new); page.fill('#passwordConfirmation', new); page.click('form button[type=submit]')
    page.wait_for_url(lambda u: '/password/' not in u, timeout=15000)
    print('dopo reset ->', page.url, 'avatar' if page.locator('.user-menu').count() else 'NO AVATAR')
with sync_playwright() as p:
    b=p.chromium.launch()
    for pw in ['Nuova-pass-123','password']:
        ctx=b.new_context(); pg=ctx.new_page(); reset(pg, pw); ctx.close()
    ctx=b.new_context(); pg=ctx.new_page(); pg.goto(f'{BASE}/it/auth/login'); pg.fill('#email',EMAIL); pg.fill('#password','password'); pg.click('form button[type=submit]'); pg.wait_for_url(lambda u:'/auth/login' not in u, timeout=15000); print('login con password ripristinata OK', pg.url)
    b.close()
