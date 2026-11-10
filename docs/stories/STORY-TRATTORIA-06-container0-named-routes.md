---
title: "Link interni via route('container0.index') — no url() hardcoded"
type: story
module: Trattoria
status: done
created: 2026-11-10
updated: 2026-11-10
tags: [routing, container0, locale, blade, tenant-config]
qmd: "trattoria container0.index route url localhost restaurant.local tenant xra pub_theme"
related:
  - ../customer-flows-contract.md
  - ../README.md
---

# Link interni via `route('container0.index')`

## Obiettivo

Sostituire `{{ url('/menu') }}` e simili con
`{{ route('container0.index', ['container0' => 'menu']) }}` in tutte le view
del tema, così i link pubblici:

- includono il prefisso locale (`/it/menu`, non `/menu`);
- usano `APP_URL=http://restaurant.local` (tenant restaurant → tema Trattoria);
- restano nominati e migrabili se gli slug cambiano.

## Contesto tenant (perché si legge `config/local/restaurant/xra.php`)

`TenantServiceProvider::mergeConfigs()` carica `config/local/<tenant>/*.php`
in `config()`. `xra.php` è l'interruttore del tenant: `pub_theme=Trattoria`,
`register_pub_theme=true`, `main_module=User`, `primary_lang=it`. Il tenant
si risolve dall'Host: `restaurant.local` → `config/local/restaurant/`;
`localhost`/`127.0.0.1` → `config/localhost/` (tema TwentyOne).

## Cambiamenti

- `components/navigation.blade.php`, `components/footer.blade.php`,
  `pages/index.blade.php`, `pages/menu.blade.php`, `pages/ordine.blade.php`,
  `pages/prenota.blade.php`: tutti i `url('/...')` → `route('container0.index', …)`;
  `home` resta `route('home')`.
- `/asporto` e `/servizio-al-tavolo` non esistono come slug: ora puntano a
  `ordine?mode=takeaway` e `ordine?mode=dine_in` (allineati al contratto
  `customer-flows-contract.md`).
- Form `action=` idem: `ordine` (GET verso menu) e `prenota`.

## Verifica

- `curl -H "Host: restaurant.local" /it/{,menu,prenota,ordine}` → 200/301.
- HTML generato: `href="http://restaurant.local/it/menu"`,
  `/it/ordine?mode=dine_in`, `/it/prenota` — prefisso locale corretto.

## Fuori scope

`Themes/TwentyOne` usa `url('/'.locale.'/...')` per il dominio Predict:
stesso pattern fragile ma tenant diverso; da valutare in story a parte.

## Da fare ambiente

`restaurant.local` non risolve in WSL: servono sudo per
`/etc/hosts` (+ `generateHosts=false` in `/etc/wsl.conf` per persistenza).
