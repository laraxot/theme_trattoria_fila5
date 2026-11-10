---
id: E-TRATTORIA-1.S5
title: Accessibilità performance e test E2E mobile
type: story
status: planned
epic: E-TRATTORIA-1
priority: P1
story_points: 8
depends_on: [E-TRATTORIA-1.S2, E-TRATTORIA-1.S3, E-TRATTORIA-1.S4]
created: 2026-09-13
repository: https://github.com/festionali/base_restaurant_fila5
---

# Story E-TRATTORIA-1.S5 — Quality gate

Come titolare voglio flussi verificati su dispositivi reali e browser mobili per evitare ordini o prenotazioni persi.

## Gap verificato

La story 1.14 cita Lighthouse e cross-browser, ma nel tema non esistono asset, test browser o PWA/offline queue.

## Criteri di accettazione

1. WCAG AA: contrasto, tastiera, focus, label/errori e screen reader verificati.
2. Touch target ≥44px, viewport/safe-area e layout testati su iOS Safari e Android Chrome.
3. Lighthouse mobile ≥90 e CLS <0.1 sui percorsi principali.
4. Test browser coprono menu/booking, takeaway/table-service e waiter ordering, inclusi errori/retry.
5. L’eventuale PWA/offline queue è documentata come incremento separato e non nasconde fallimenti di rete.

## GitHub (tracciamento)

| Risorsa | Ruolo |
|---|---|
| [issue #12](https://github.com/festionali/base_restaurant_fila5/issues/12) | Quality gate mobile |
| [discussion #17](https://github.com/festionali/base_restaurant_fila5/discussions/17) | Criteri qualità |
