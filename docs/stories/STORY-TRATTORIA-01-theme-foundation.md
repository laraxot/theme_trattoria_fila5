---
id: E-TRATTORIA-1.S1
title: Trattoria theme foundation shell e asset responsive
type: story
status: planned
epic: E-TRATTORIA-1
priority: P0
story_points: 8
depends_on: []
created: 2026-09-13
repository: https://github.com/festionali/base_restaurant_fila5
---

# Story E-TRATTORIA-1.S1 — Foundation

Come cliente o cameriere voglio una shell Trattoria coerente e utilizzabile da telefono, tablet e desktop per poter iniziare ogni flusso senza frizione.

## Gap verificato

`resources/views`, `resources/css`, `resources/js`, `public` e `database/seeds` sono vuote. Il service provider pubblica asset inesistenti e non esiste un layout Folio/Blade.

## Criteri di accettazione

1. Esistono layout, header, footer, navigazione mobile e componenti condivisi caricati dal tema.
2. Tailwind/CSS e JavaScript del tema sono realmente compilabili e pubblicabili dal provider.
3. Palette, tipografia e breakpoint di `docs/theme.md` sono token applicati, mobile-first.
4. Touch target minimo 44px, focus visibile, contrasto WCAG AA e safe-area mobile.
5. Nessun path pubblicato dal provider punta a una directory vuota o inesistente.

## Attività

- [ ] Creare shell Folio compatibile con le convenzioni del progetto.
- [ ] Aggiungere componenti menu, card, carrello, form e feedback riutilizzabili.
- [ ] Configurare asset CSS/JS, font e immagini ottimizzate.
- [ ] Aggiungere test di rendering e snapshot responsive.

## Dipendenze

Restaurant 1.9 per le pagine operative; Restaurant 1.14 viene assorbita come requisito responsive di questa story e non va duplicata.

## GitHub (tracciamento)

| Risorsa | Ruolo |
|---|---|
| [issue #8](https://github.com/festionali/base_restaurant_fila5/issues/8) | Implementazione foundation |
| [discussion #13](https://github.com/festionali/base_restaurant_fila5/discussions/13) | Decisioni shell e asset |
