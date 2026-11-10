---
id: trattoria-navigation-integration
slug: navigation-integration
title: "Integrazione navigazione — Trattoria theme"
description: "Come i link interni del tema Trattoria (home, menu, prenota, ordine) usano route named Folio, passaggio parametri, e collegamento ai modelli Restaurant."
document_type: reference
type: reference
category: documentation
status: active
module: Trattoria
tags: [trattoria, navigation, routing, folio, named-routes, restaurant]
created: "2026-11-10"
updated: "2026-11-10"
qmd: "trattoria navigation integration folio named routes link interni"
issues: []
discussions: []
related:
  - ./README.md
  - ./customer-flows-contract.md
  - ../../../Modules/Restaurant/docs/navigation-translations.md
  - ../../../Modules/Xot/docs/console/commands/xot-asset-links.md
---

# Integrazione navigazione — Trattoria

Tutti i link interni usano **route named Folio** (`route('container0.index', ...)`),
mai `url()` o percorsi hardcoded. È la regola del progetto
(`folio-routing-religion`, vedi `Modules/Xot/docs/console/commands/xot-asset-links.md`).

## Mappa route pubbliche (FO)

| Pagina | Route Folio | Parametri `route()` | Template Folio |
|--------|-------------|---------------------|----------------|
| Home | `home` | — | `resources/views/pages/index.blade.php` |
| Menu | `container0.index` | `['container0' => 'menu']` | `resources/views/pages/[container0]/[slug0]/[restaurant]/index.blade.php` |
| Prenota | `container0.index` | `['container0' => 'prenota']` | `resources/views/pages/prenota.blade.php` |
| Ordine | `container0.index` | `['container0' => 'ordine']` | `resources/views/pages/ordine.blade.php` |

## Esempi Blade

```blade
{{-- Home --}}
<a href="{{ route('home') }}">{{ __('trattoria::nav.home') }}</a>

{{-- Menu --}}
<a href="{{ route('container0.index', ['container0' => 'menu']) }}">{{ __('trattoria::nav.menu') }}</a>

{{-- Prenota --}}
<a href="{{ route('container0.index', ['container0' => 'prenota']) }}">{{ __('trattoria::nav.book_table') }}</a>

{{-- Ordine --}}
<a href="{{ route('container0.index', ['container0' => 'ordine']) }}">{{ __('trattoria::nav.order') }}</a>
```

## Traduzioni nav (lang/en/it/nav.php)

```php
return [
    'home' => 'Home',
    'menu' => 'Menu',
    'book_table' => 'Prenota un tavolo',
    'order' => 'Ordina online',
];
```

## Passaggio parametri al dominio Restaurant

Le pagine `prenota` e `ordine` sono *entry point* pass-through: il tema
raccoglie input utente (mode ordine, data/ora/party size prenotazione) e
invia via `GET`/`POST` a route che il modulo Restaurant gestirà (es.
`Restaurant\Actions\CreateOrderAction`, `Restaurant\Actions\CreateReservationAction`).

Esempio form ordine (`resources/views/pages/ordine.blade.php`):

```blade
<form method="get" action="{{ route('container0.index', ['container0' => 'menu']) }}" data-contract="restaurant.orders.create">
    <input type="radio" name="mode" value="dine_in" @checked($selectedMode === 'dine_in')>
    <input type="radio" name="mode" value="takeaway" @checked($selectedMode === 'takeaway')>
    <input type="radio" name="mode" value="delivery" @checked($selectedMode === 'delivery')>
    <input type="number" name="covers" min="1" max="30" value="1">
    <input type="text" name="table_id" inputmode="numeric">
    <button type="submit">{{ __('trattoria::order.go_to_menu') }}</button>
</form>
```

Parametri passati al menu: `?mode=dine_in&covers=4&table_id=12`. Il controller
/ action del modulo Restaurant legge `request()->input('mode')`,
`request()->input('covers')`, `request()->input('table_id')` e crea
`Restaurant\Models\Order` con `order_type = $mode`.

## Regole

- **Mai** `url('/menu')`, **sempre** `route('container0.index', ['container0' => 'menu'])`.
- Parametri query string sono il contratto pubblico (no session, no cookie).
- Traduzioni in `lang/{it,en}/nav.php` + `lang/{it,en}/order.php` +
  `lang/{it,en}/reservation.php`.
- Se aggiungi una pagina FO: crea template Folio, aggiungi traduzioni nav,
  aggiorna questo documento.