---
id: E-TRATTORIA-1.S3
title: Menu pubblico e prenotazione tavolo cliente
type: story
status: planned
epic: E-TRATTORIA-1
priority: P0
story_points: 13
depends_on: [E-TRATTORIA-1.S1]
created: 2026-09-13
repository: https://github.com/festionali/base_restaurant_fila5
---

# Story E-TRATTORIA-1.S3 — Customer menu e booking

Come cliente voglio consultare il menu e prenotare un tavolo dal telefono per completare la visita senza telefonare al locale.

## Gap verificato

`ReservationResource` copre solo il back-office Filament. Le route web/API sono vuote e il tema non ha pagine pubbliche, menu o form di prenotazione.

## Criteri di accettazione

1. Il menu pubblico mostra categorie persistenti, ricerca, disponibilità, prezzi, allergeni, immagini e modificatori.
2. Il cliente sceglie data, ora, coperti e sede/zona; la disponibilità è verificata server-side.
3. Il form raccoglie contatto e note, valida errori e impedisce doppie prenotazioni.
4. La conferma mostra codice/stato e non espone dati di altri clienti.
5. Il percorso funziona da mobile con tastiera, screen reader e link condivisibile/QR.

## Attività

- [ ] Creare pagine Folio e componenti tema per menu e booking.
- [ ] Integrare `Reservation` e Actions esistenti senza logica duplicata.
- [ ] Aggiungere test di disponibilità, validation e browser flow.

## GitHub (tracciamento)

| Risorsa | Ruolo |
|---|---|
| [issue #10](https://github.com/festionali/base_restaurant_fila5/issues/10) | Menu e prenotazione |
| [discussion #15](https://github.com/festionali/base_restaurant_fila5/discussions/15) | Contratto percorso pubblico |
