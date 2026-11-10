---
id: E-TRATTORIA-1.S2
title: Ordering mobile handheld per camerieri
type: story
status: planned
epic: E-TRATTORIA-1
priority: P0
story_points: 13
depends_on: [E-TRATTORIA-1.S1]
created: 2026-09-13
repository: https://github.com/festionali/base_restaurant_fila5
---

# Story E-TRATTORIA-1.S2 — Waiter handheld ordering

Come cameriere voglio aprire e aggiornare una comanda dal telefono con controlli touch per servire un tavolo senza tornare alla cassa.

## Gap verificato

Non esiste UI waiter nel tema, non esiste una pagina pubblica o operativa per la comanda e non è presente un’Action canonica di creazione ordine. Il dominio Restaurant contiene modelli e Actions di calcolo/chiusura da riusare.

## Criteri di accettazione

1. Il cameriere autenticato vede solo sedi, zone e tavoli autorizzati.
2. Può aprire una comanda, aggiungere prodotti e modificatori, note e quantità con touch target ≥44px.
3. Il riepilogo resta visibile, supporta modifica/rimozione e richiede conferma prima dell’invio.
4. L’invio separa cucina/bar, mostra feedback e gestisce retry senza duplicare la comanda.
5. Stati tavolo e ordine si aggiornano con polling leggero e indicatore di sincronizzazione.

## Attività

- [ ] Implementare pagina handheld e selettore sala/tavolo.
- [ ] Collegare Actions Restaurant e contratti di autorizzazione.
- [ ] Aggiungere idempotenza, retry e test feature/browser.

## GitHub (tracciamento)

| Risorsa | Ruolo |
|---|---|
| [issue #9](https://github.com/festionali/base_restaurant_fila5/issues/9) | Implementazione waiter |
| [discussion #14](https://github.com/festionali/base_restaurant_fila5/discussions/14) | UX handheld e sincronizzazione |
