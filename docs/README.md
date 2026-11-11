---
id: trattoria-docs-readme
slug: readme
title: "Documentazione del tema Trattoria"
description: "Punto di ingresso della docs del tema Trattoria: tema pubblico attivo del tenant restaurant, mappa delle cartelle, documenti chiave e regole di manutenzione."
document_type: index
type: index
category: documentation
status: active
module: Trattoria
tags: [trattoria, tema, docs, indice, restaurant]
created: "2026-11-10"
updated: "2026-11-10"
qmd: "tema trattoria docs indice restaurant pub_theme xra ristorante"
issues: []
discussions: []
related:
  - ./00-INDEX.md
  - ./theme.md
  - ./customer-flows-contract.md
  - ./navigation-integration.md
  - ./cms-pages-and-blocks.md
  - ./layout-cms-sections.md
  - ./layouts.md
  - ./award-sites-audit.md
  - ./stories/STORY-TRATTORIA-01-theme-foundation.md
  - ../../../Modules/Restaurant/docs/
  - ../../../Modules/Xot/docs/index.md
---

# Documentazione del tema Trattoria

Tema pubblico del tenant `restaurant` (ristorante). È il tema attivo quando
`config/local/restaurant/xra.php` ha `pub_theme => 'Trattoria'` e
`register_pub_theme => true`.

## Attivazione (tenant config)

`TenantServiceProvider::mergeConfigs()` carica ogni file di
`laravel/config/local/<tenant>/` dentro `config('<file>')`. Per il dominio
`restaurant.local` (tenant `restaurant`) valgono:

- `pub_theme => 'Trattoria'` — tema FO registrato e renderizzato
- `register_pub_theme => true` — provider del tema registrato
- `register_adm_theme => false` — nessun tema admin dedicato
- `main_module => 'User'`, `primary_lang => 'it'`
- `APP_URL=http://restaurant.local` in `laravel/.env` (generazione URL)

Il tenant si risolve dall'header `Host` via `Modules/Tenant`
(`GetTenantNameAction`): `restaurant.local` → `config/local/restaurant/`,
`localhost`/`127.0.0.1` → `config/localhost/` (tema `TwentyOne`).

## Mappa docs

| File | Scopo |
|------|-------|
| [00-INDEX.md](00-INDEX.md) | Indice storico della cartella docs |
| [theme.md](theme.md) | Palette colori, tipografia, classi CSS e layout implementati |
| [customer-flows-contract.md](customer-flows-contract.md) | Contratto pagine pubbliche (home, `menu`, `prenota`, `ordine`), link interni e campi form → modelli Restaurant |
| [navigation-integration.md](navigation-integration.md) | Link interni del tema (home, menu, prenota, ordine): route Folio con nome, parametri e collegamento ai modelli Restaurant |
| [award-sites-audit.md](award-sites-audit.md) | Audit dei pattern dei siti ristorante premiati e loro applicazione al tema |
| [layouts.md](layouts.md) | Componenti layout (`main`, `app`, alias), header e footer per ristorante, regole per chi modifica i layout |
| [cms-pages-and-blocks.md](cms-pages-and-blocks.md) | Pagine CMS, blocchi e route Folio del tenant restaurant |
| [layout-cms-sections.md](layout-cms-sections.md) | Layout `main`/`app`, `<x-cms::head>`, sezioni CMS header/footer, namespace `pub_theme` per le traduzioni |
| [stories/](stories/) | Story BMAD epic `E-TRATTORIA-1` (foundation, waiter ordering, customer menu/booking, order modes, quality gate), più `STORY-TRATTORIA-06-container0-named-routes` (link interni via route) e `trattoria-home-motion-and-polish` (movimento e pulizia della home) |

## Asset pubblici

`public_html/themes/Trattoria` è il symlink relativo `../../laravel/Themes/Trattoria/public`
(creato da `php artisan xot:theme-link`, vedi
`Modules/Xot/docs/console/commands/xot-asset-links.md`). URL asset:
`/themes/Trattoria/...`.

> **`public_html/` è la document root del progetto: non va mai cancellata, svuotata o sostituita.**
> Per rigenerare gli asset si sovrascrivono i file o si ricrea il solo symlink del tema.
> Regole complete: `Modules/Restaurant/docs/regole-progetto.md`.

## Regole

- Template engine: Folio/Volt/Laraxot — no controller FO.
- Dati dal dominio Restaurant via modelli/Actions esistenti; il tema è
  presentation-only (vedi regola `theme-vestito-page-shell`).
- Nuovi documenti: frontmatter obbligatorio, niente date nel filename.
- Link interni: sempre `route('container0.index', [...])` o `route('home')`, mai `url('/...')` (vedi [customer-flows-contract.md](customer-flows-contract.md#link-interni)).