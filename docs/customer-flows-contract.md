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

Le form hanno gli attributi `data-contract="restaurant.reservations.create"` e `data-contract="restaurant.orders.create"`. Il collegamento applicativo deve essere fatto tramite route Folio/Volt e Action del modulo Restaurant; il tema non duplica modelli né logica di dominio. La prenotazione usa lo stato iniziale `confirmed` previsto da `ReservationStatusEnum`.
