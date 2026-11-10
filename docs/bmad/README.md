---
title: "BMAD — Trattoria theme (CMS)"
type: index
status: active
theme: Trattoria
created: "2026-11-10"
updated: "2026-11-10"
tags: [bmad, trattoria, theme, filament-blueprint, docs]
qmd: "trattoria bmad index filament blueprint stories frontoffice"
---

# BMAD — Trattoria

- [filament-blueprint.md](filament-blueprint.md) — confini Filament/XotBase e integrazione FO/BO.
- [../stories/](../stories/) — story BMAD epic `E-TRATTORIA-1`.
- [../customer-flows-contract.md](../customer-flows-contract.md) — contratto pagine e form.
- [../theme.md](../theme.md) — design tokens, classi, layout.
- [../layout-cms-sections.md](../layout-cms-sections.md) — layout CMS (main.blade.php, app.blade.php, x-cms::head, x-section header/footer).
- [../cms-pages-and-blocks.md](../cms-pages-and-blocks.md) — CMS sections and blocks (pub_theme).
- [../components/blocks/public_menu.md](../components/blocks/public_menu.md) — public_menu design & code (mobile navigation, allergen icons, photo integration, 5-element translation prototype).
- [../components/blocks/menu/restaurant.blade.php](../components/blocks/menu/restaurant.blade.php) — filterable menu with dietary restrictions and allergy toggles (compiles with code validation).
- [../screenshots/](../screenshots/) — screenshot series for public_menu and menu/restaurant (mobile, tablet, desktop).

Owner docs: `laravel/Themes/Trattoria/docs/`. Dominio: `laravel/Modules/Restaurant/docs/`.

## Perché questo README?

Tutti gli asset trattoria sono nel VCS. Lo scopo di questa pagina è uno studio BMAD: motivazioni, passi successivi, stato corrente, fonti e osservazioni, il perché dei cambiamenti, e quale altro lavoro segue da qui.

## Riferimento rapido

| File | Cosa fa |
|------|-----|
| layout-cms-sections.md | Limiti del tema (app/main), sezioni header/footer, namespace traduzioni `pub_theme`. |
| cms-pages-and-blocks.md | Sezioni CMS `header/v1` / `footer/v1` — i template del tema. |
| components/blocks/public_menu.md | Documento di design per il menu pubblico, mobile-first, icone allergen, gestione foto, prototypes. |
| components/blocks/menu/restaurant.blade.php | Componente filtrato per il menu pubblico (`restaurants.{slug}.menu`) con filtri dietà, cambio lingua. |

## Come funziona in breve

1. **Richiesta:** `/{lang}/restaurants/{slug}` → resolve restaurant → pagina header/footer __restaurant__.
2. **Menu:** `/{lang}/restaurants/{slug}/menu` → componente `restaurant.blade.php` con filtri allergen (vegano, vegetariano, no maiale, kosher) e aggiornamento in tempo reale del conteggio piatti con 4 icone allergen color-coded e supporti aria-label.
3. **Foto & Allergen:** Cliccando una icona si vedono dettagli e foto 2-3 piatti che contengono quell'allergene (visuale in una modale piccola).
4. **Mobile-first:** Messa a punto tramite device emulation (390px/768px/1024px), touch target ≥48px, uno stack verticale del menu con regole di dimensioni del font, griglia mobile-responsive per le icone allergen, barra filtri sticky.
5. **Traduzioni:** Chiavi 5-element prototipo (contesto.collezione.item.type) in `Themes/Trattoria/lang/it/en/public_menu.php` — assicura mappatura esatta.

## Stato corrente

- ✅ *route, pages e seeders:* struttura Folio CMS attivi.
- ✅ *auth component:* template `header/v1` / `footer/v1` usa il nuovo namespace `pub_theme::nav.*` (no risiduali trattoria::).
- ✅ *public_menu*: file design (__README__, __screenshots__) e componenti (`components/blocks/public_menu.md`, `menu/restaurant.blade.php`) completi.
- ✅ *key compliance*: 16 chiavi it/en con 5-element structure (contesto.collezione.item.type). Documentate in wiki_observe con tags rilevanti.
- ✅ *symlink assets:* `public_html/assets/xot` / `geo` funzionanti.
- ✅ *components layout:* `app.blade.php` è un wrapper per `main.blade.php`, header/footer CMS tramite `<x-section>`.

## Le altre pagine?

- **filament-blueprint.md** → perché non estendiamo Filament direttamente (XotBase/LangBase), come istanziamo le vere Features (actions) → per cambiare un utente (tenant).
- **customer-flows-contract.md** → come l'utente passa dalla home alla scheda alla lista menu alla prenotazione (FLOW-1 → FLOW-2 → FLOW-3).
- **theme.md** → design tokens (risolve `pub_theme::` namespaces).
- **stories/** — epic ep product-EM-TRATTORIA-1 con azioni a valle.

## Note di fine

- **MDS:** Markdown standard. No retrocompatibilità magica.
- **Assets:** sistemi di build (Vite/Bun/NGINX) hanno accesso via `public_html/` grazie ai symlink.
- **Note per lo sviluppo:** `php artisan optimize:clear` dopo qualsiasi cambio CSS/JS, usare `php artisan route:list | grep container0` per vedere i nomi delle rotte Folio, `php artisan route:list | grep ^/restaurants` per vedere le rotte REST (REST non è attualmente utilizzato).