---
title: "Trattoria customer flows contract"
type: "technical-note"
tags: [trattoria, menu, prenotazioni, ordini, folio]
created: "2026-09-13"
updated: "2026-09-13"
---

# Flussi cliente

Le pagine Folio del tema espongono quattro superfici responsive:

- `/` presenta le tre modalità di servizio;
- `/menu` legge `ProductCategory` e `Product` attivi del modulo Restaurant;
- `/prenota` raccoglie i campi di `Reservation` (`customer_name`, `phone`, `email`, `party_size`, `reservation_date`, `reservation_time`, `notes`);
- `/ordine` sceglie `Order.order_type` (`dine_in`, `takeaway`, `delivery`) e passa coperti e tavolo al menu.

<<<<<<< .merge_file_5YtKg3
Il tema non ha pagine Folio dedicate per questi flussi: tutto passa dalle pagine generiche
`resources/views/pages/[container0]/...` che risolvono una pagina CMS (`ResolvePageAction`) e ne
rendono i blocchi. Logica e dati: Action del modulo Restaurant, mai controller o route nel modulo.
Fonte di verita' dettagliata: `Modules/Restaurant/docs/public-menu-table-cms.md`.

| URL | Route Folio | Pagina CMS | Blocchi principali | Scopo |
|-----|-------------|------------|--------------------|-------|
| `/{lang}` | `home` | `home` | hero, griglie | Landing |
| `/{lang}/menu` | `container0.index` | `menu.index` | hero, cards, `menu/categories` | Sezioni del menu |
| `/{lang}/menu/{slug}` | `container0.view` | `menu.view` (fallback) | `public_menu` | Menu del ristorante dal QR, filtri allergeni e regimi |
| `/{lang}/restaurants/{slug}` | `container0.view` | `restaurants.view` | `restaurant_detail` | Minisito: contatti, orari, CTA menu/prenota |
| `/{lang}/restaurants/{slug}/menu` | `container0.container1.view` | `restaurants.menu` | hero, `menu/categories` | Menu nel minisito |
| `/{lang}/restaurants/{slug}/book` | `container0.container1.view` | `restaurants.book` | hero, `reservation_form` | Prenotazione per quel ristorante |
| `/{lang}/prenota` | `container0.index` | `prenota.index` | hero, `reservation_form` | Prenotazione generica |
| `/{lang}/ordine` | `container0.index` | `ordine.index` | hero, cards | Scelta modalita' ordine |
| `/{lang}/table/{id}/qr` | `container0.container1.view` | `table.view` | `table_order` | Ordine al tavolo da QR |

`resources/views/pages/prenota.blade.php.bak` e `ordine.blade.php.bak` sono le vecchie pagine
dedicate, sostituite dalle pagine CMS: non sono piu' servite.

## Modelli Restaurant collegati

Il "ristorante" e' il **tenant** del modulo User (`XotData::getTenantClass()`, chiave stringa,
`slug`): non esiste un modello `Restaurant\Models\Restaurant`.

| Modello | Campi usati | Usato in |
|---------|-------------|----------|
| Tenant (modulo User) | `id`, `slug`, `name`, contatti, `settings` (orari, slogan, logo) | menu, minisito, prenota |
| `Restaurant\Models\ProductCategory` | `name`, `sort_order`, `is_active`, `tenant_id` | menu |
| `Restaurant\Models\Product` | `name`, `description`, `price`, `images`, `allergens`, `tenant_id`; `allergenCases()` | menu |
| `Restaurant\Models\Ingredient` | `name`, `allergens` | menu (dettaglio piatto) |
| `Restaurant\Models\Reservation` | `customer_name`, `email`, `phone`, `party_size`, `reservation_date`, `reservation_time`, `notes`, `status`, `tenant_id` | prenota |
| `Restaurant\Models\Order` | `order_type` (`dine_in|takeaway|delivery`), `covers`, `table_id` | ordine, tavolo |

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
=======
Le form hanno gli attributi `data-contract="restaurant.reservations.create"` e `data-contract="restaurant.orders.create"`. Il collegamento applicativo deve essere fatto tramite route Folio/Volt e Action del modulo Restaurant; il tema non duplica modelli né logica di dominio. La prenotazione usa lo stato iniziale `confirmed` previsto da `ReservationStatusEnum`.
>>>>>>> .merge_file_q3M4L0
