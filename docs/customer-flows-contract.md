---
id: trattoria-customer-flows-contract
slug: customer-flows-contract
title: "Contratto flussi cliente — Trattoria theme"
description: "Contratto delle pagine pubbliche FO del tema Trattoria: home, menu, prenota, ordine. Campi form, modelli Restaurant collegati, link interni via route named Folio."
document_type: contract
type: contract
category: documentation
status: active
module: Trattoria
tags: [trattoria, customer-flows, contract, order, reservation, restaurant-models]
created: "2026-11-10"
updated: "2026-11-10"
qmd: "trattoria customer flows contract order reservation restaurant model"
issues: []
discussions: []
related:
  - ./README.md
  - ./navigation-integration.md
  - ../../../Modules/Restaurant/docs/README.md
  - ../../../Modules/Restaurant/docs/stories/STORY-TRATTORIA-01-theme-foundation.md
---

# Contratto flussi cliente — Trattoria

Questo documento definisce il contratto tra il tema pubblico `Trattoria`
(FO, tenant `restaurant`) e il dominio `Restaurant` (modelli, Actions,
database). È il riferimento unico per chi implementa pagine FO o modifica
il flusso ordine/prenotazione.

## Pagine pubbliche

| Pagina | Template Folio | Route | Scopo |
|--------|---------------|-------|-------|
| Home | `resources/views/pages/index.blade.php` | `home` | Landing del ristorante |
| Menu | `resources/views/pages/[container0]/[slug0]/[restaurant]/index.blade.php` | `container0.index?container0=menu` | Lista piatti del ristorante attivo |
| Prenota | `resources/views/pages/prenota.blade.php` | `container0.index?container0=prenota` | Form prenotazione tavolo |
| Ordine | `resources/views/pages/ordine.blade.php` | `container0.index?container0=ordine` | Scelta modalità ordine + passaggio al menu |

## Modelli Restaurant collegati

| Modello | Campo chiave | Usato in |
|---------|-------------|----------|
| `Restaurant\Models\Restaurant` | `id`, `slug`, `title` | Home, menu (ristorante attivo) |
| `Restaurant\Models\Reservation` | `name`, `email`, `phone`, `party_size`, `reservation_date`, `reservation_time`, `notes`, `status` | Prenota |
| `Restaurant\Models\Order` | `order_type` (`dine_in|takeaway|delivery`), `covers`, `table_id` | Ordine |

## Contratto form Prenota / book ristorante

Template: blocco CMS `reservation_form` (`components/blocks/reservation_form.blade.php`),
usato sia dalla pagina generica `/{lang}/prenota` sia dalla pagina contestuale
`/{lang}/restaurants/{slug}/book` (pagina CMS `restaurants.book`, blocco
`reservation_form` con `$item` = ristorante).
Metodo: `POST` Livewire/Volt (componente `reservation-form`)
Azione collegata: `Restaurant\Actions\Reservations\CreatePublicReservationAction`
— crea `Reservation` con `status = confirmed` e `tenant_id` del ristorante
quando la pagina e' contestuale (`$item`); altrimenti tenant corrente.

Campi:

| Campo | Tipo | Obbligatorio | Nota |
|-------|------|--------------|------|
| `customer_name` | testo | Sì | Nome e cognome |
| `phone` | tel | Sì | Telefono |
| `email` | email | No | Email di contatto |
| `party_size` | stepper 1-12 | Sì | Persone |
| `reservation_date` | date | Sì | Min oggi |
| `reservation_time` | time | Sì | Orario |
| `notes` | textarea | No | Allergie/esigenze |

Risposta: nessun redirect — messaggio di successo inline
(`$success_message` del blocco CMS o `pub_theme::reservation.success`).

## Contratto form Ordine

Template: `pages/ordine.blade.php`
Metodo: `GET` verso il menu (pass-through)
Azione collegata: `Restaurant\Actions\CreateOrderAction` (in attesa di implementazione)

Campi:

| Campo | Tipo | Valori | Nota |
|-------|------|--------|------|
| `mode` | radio | `dine_in`, `takeaway`, `delivery` | Modalità ordine |
| `covers` | number | 1-30 | Coperti (solo dine_in) |
| `table_id` | testo/numerico | — | Numero tavolo (solo dine_in) |

Risposta: redirect al menu con i parametri query:
`?mode=dine_in&covers=2&table_id=5`. Il modulo Restaurant legge
`request()->input()` e crea l'ordine.

## Link interni

Tutti i link interni usano `route()` con nomi Folio:

```blade
{{ route('home') }}
{{ route('container0.index', ['container0' => 'menu']) }}
{{ route('container0.index', ['container0' => 'prenota']) }}
{{ route('container0.index', ['container0' => 'ordine']) }}
```

Mai `url()` o percorsi hardcoded. Vedere [navigation-integration.md](navigation-integration.md).

## Regole

- Tema è **presentation-only**: nessun dato di dominio dentro le viste, solo
  parametri passati via route/query string.
- Traduzioni: `lang/{it,en}/nav.php`, `order.php`, `reservation.php`.
- Se aggiungi un campo form: aggiorna questo contratto + lang + template +
  navigation-integration.md.
- Se modifichi un modello Restaurant: sincronizza qui i campi usati.