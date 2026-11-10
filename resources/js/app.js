document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-nav-toggle]');
    const menu = document.querySelector('[data-nav-menu]');
    if (!toggle || !menu) return;

    const label = toggle.querySelector('.sr-only');
    const glyph = toggle.querySelector('[data-nav-glyph]');

    const setOpen = (open) => {
        menu.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        if (label) label.textContent = open ? 'Chiudi il menu' : 'Apri il menu';
        if (glyph) glyph.textContent = open ? '×' : '☰';
    };

    toggle.addEventListener('click', () => setOpen(!menu.classList.contains('is-open')));

    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape' || !menu.classList.contains('is-open')) return;
        setOpen(false);
        toggle.focus();
    });

    const desktop = window.matchMedia('(min-width: 768px)');
    desktop.addEventListener('change', (event) => {
        if (event.matches) setOpen(false);
    });
});
