---
id: trattoria-cms-pages-blocks
slug: cms-pages-and-blocks
title: "Pagine CMS e blocchi del tema Trattoria"
description: "Struttura route Folio [container0]/[slug0], contratto delle pagine JSON in config/local/restaurant/database/content/pages e schema dei blocchi in components/blocks."
document_type: guide
type: guide
category: architecture
status: active
module: Trattoria
tags: [trattoria, cms, folio, volt, blocchi, i18n, restaurant]
created: "2026-11-10"
updated: "2026-11-10"
qmd: "trattoria cms blocchi content_blocks container0 slug0 pagine json"
issues: []
discussions: []
related:
  - ./README.md
  - ./theme.md
  - ../../Modules/Cms/docs/
  - ../../Modules/Restaurant/docs/public-menu-table-cms.md
---

# Pagine CMS e blocchi — Trattoria

## Struttura route (Folio + Volt)

Solo placeholder contestuali, mai directory semantiche:

```
resources/views/pages/
├── index.blade.php                        # /  (home)
├── [container0]/index.blade.php           # /{container0}        → slug CMS `{container0}.index`
└── [container0]/[slug0]/index.blade.php   # /{container0}/{slug0} → ResolvePageAction → x-page
    └── [container1]/[slug1]/…             # profondità 3-8 segmenti, stesso resolver su (container0, slug0)
```

`[container0]/[slug0]` (e le varianti profonde) montano `ResolvePageAction`,
che prova `{container0}.{slug0}` e ricade su `{container0}.view`. Il risultato
va a `<x-page side="content" :slug="$pageSlug" :data="$data" />` con
`PageSlugMiddleware`.

## Contratto pagine JSON

File in `laravel/config/local/restaurant/database/content/pages/{slug}.json`:

- `slug` = nome CMS (`menu.index`, `menu.view`, `table.view`, …)
- `content_blocks` = `{ "it": [...], "en": [...] }` — ogni blocco:
  ```json
  { "type": "hero", "slug": "hero",
    "data": { "view": "pub_theme::components.blocks.hero", "title": "…" } }
  ```
- `data.view` è obbligatorio e deve esistere (`BlockData` lancia altrimenti).
- `middleware` solo se serve (es. `["throttle:60,1"]`).

## Blocchi disponibili (`components/blocks/`)

| type | view | Uso |
|---|---|---|
| `hero` | `pub_theme::components.blocks.hero` | titolo, subtitle, actions[{label,route,params,variant}], aside |
| `cards` | `pub_theme::components.blocks.cards` | griglia card con action route/params |
| `menu` | `pub_theme::components.blocks.menu.categories` | categorie/piatti del tenant |
| `reservation_form` | `pub_theme::components.blocks.reservation_form` | form prenotazione → `CreatePublicReservationAction` |
| `public_menu` | `pub_theme::components.blocks.public_menu` | menu pubblico `/menu/{slug-ristorante}` con filtro `?senza[]=` |
| `table_order` | `pub_theme::components.blocks.table_order` | stato ordine tavolo `/table/{id}` (404 se ignoto) |

`public_menu` legge `request()->route('slug0')` come slug ristorante;
`table_order` legge `slug0` come id tavolo. Funziona solo sulle route a due
segmenti (`/{container0}/{slug0}`): `/menu/{slug}` e `/table/{id}`.

## Regole

- **Niente testo in lingua nelle Blade**: solo `__('trattoria::…')` con file in
  `resources/lang/{it,en}/`.
- Le pagine JSON portano contenuto localizzato it/en nel JSON stesso.
- Le blade dei blocchi sono presentazione pura: la logica sta nelle Action del
  modulo owner (Restaurant per menu/tavoli/pr prenotazioni).

## Card piatto del menu (dish-item)

`components/blocks/menu/dish-item.blade.php` e' il parziale unico per la card piatto del menu pubblico (blocchi `menu/restaurant` e `public_menu`). Struttura: `<details class="dish">` → `<summary>` con `.dish__media` (foto 4:3 + chip `.dish__price`), `.dish__name`, `.dish__teaser`, `.dish__badges` (badge dieta + chip allergeni (icona+sigla; etichetta solo nel tooltip, il testo completo e' nella legenda)); il corpo `.dish__body` mostra `.dish__gallery`, descrizione, ingredienti con badge e riepilogo allergeni. Il toggle `+` ruota a 45° quando aperto. Immagini: `products.images[0]` copertina, tutte nella gallery; URL `asset('assets/Restaurant/'.path)` dal public del modulo Restaurant. Responsive: griglia `.dish-grid` 1 col mobile / 2 da 640px / 3 da 1024px.

## Minisito ristorante (restaurant_detail)

Il blocco `restaurant_detail` e' la home del minisito: hero con nome/lead/CTA, poi griglia `.restaurant-home__grid` (1 col mobile, 2 da 820px) con card orari (`.hours-list`) e contatti (`.contact-list` con icone `restaurant-*`). Dati via `GetRestaurantProfileAction` (tenant columns + `settings.opening_hours`). Il footer restaurant mostra indirizzo/telefono/email dello stesso profile. Le card si nascondo se il dato manca; se non ci sono contatti compare un messaggio localizzato `contact.empty.text`.

## Blocco `table_order_form` — ordinazione al tavolo

Pagina CMS `restaurants.table_order` (JSON `pages/restaurants/table_order.json`),
raggiunta da `/restaurants/{slug}/table/{num}/order`. La pagina Folio
`[container0]/[slug0]/[container1]/[slug1]/[container2]` instrada questo pattern
verso `pageSlug = restaurants.table_order` con `tableRef = slug1`.

Il blocco `components/blocks/table_order_form.blade.php` e' una vista CMS con
componente Volt inline (`@volt('table-order-form')`): menu del ristorante con
stepper quantita', carrello laterale sticky (primo su mobile), submit via
`CreateCustomerOrderAction` con idempotency key. Le etichette stanno in
`pub_theme::table_order.*` (it/en). Dettagli in
`Modules/Restaurant/docs/table-order-flow.md`.

### Ordinazione con modifiche (aggiornamento)

Il blocco `table_order_form` mostra per ogni piatto un "Personalizza"
(`dish__customize`): checkbox dei `product_modifiers` attivi (con +prezzo) e
campo note libero, solo quando il piatto e' nel carrello. Il submit invia
note + nomi modificatori e poi smista l'ordine alle stazioni
(kitchen/bar/external_bar) via `SendOrderToStationsAction`.
