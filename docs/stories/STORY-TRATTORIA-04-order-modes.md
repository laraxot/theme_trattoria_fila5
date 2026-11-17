---
id: E-TRATTORIA-1.S4
title: Checkout cliente asporto e servizio al tavolo
type: story
status: planned
epic: E-TRATTORIA-1
priority: P0
story_points: 13
depends_on: [E-TRATTORIA-1.S1, E-TRATTORIA-1.S3]
created: 2026-09-13
repository: https://github.com/festionali/base_restaurant_fila5
---

# Story E-TRATTORIA-1.S4 — Order modes

Come cliente voglio scegliere asporto o servizio al tavolo, personalizzare il menu e confermare l’ordine con un riepilogo chiaro.

## Gap verificato

`orders.order_type` è una stringa libera con default `dine_in`; mancano contratti UI, checkout cliente, orario di ritiro, stato fulfillment e distinzione esplicita dei canali.

## Criteri di accettazione

1. La prima scelta distingue dine-in/service-at-table e takeaway con copy comprensibile.
2. Il menu segue pattern fast-food: categorie rapide, card prodotto, combo/promozioni, modificatori espliciti e carrello persistente.
3. Asporto richiede sede e fascia/orario di ritiro; servizio al tavolo richiede tavolo o codice QR secondo configurazione.
4. Il riepilogo mostra quantità, allergeni, note, costi e modalità prima dell’invio idempotente.
5. Il cliente riceve codice e stato a step (ricevuto, in preparazione, pronto, servito/ritirato).

## Attività

- [ ] Definire enum/contratti di fulfillment nel modulo Restaurant.
- [ ] Implementare carrello e checkout responsive nel tema.
- [ ] Collegare KDS e notifiche quando le story Restaurant 1.9/2.1 saranno pronte.

## GitHub (tracciamento)

| Risorsa | Ruolo |
|---|---|
| [issue #11](https://github.com/festionali/base_restaurant_fila5/issues/11) | Canali asporto e tavolo |
| [discussion #16](https://github.com/festionali/base_restaurant_fila5/discussions/16) | Contratti dei canali |
