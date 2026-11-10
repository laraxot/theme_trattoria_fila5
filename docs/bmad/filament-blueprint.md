---
title: "Filament Blueprint — Trattoria (Theme)"
type: filament-blueprint
status: active
theme: Trattoria
created: 2026-11-10
updated: 2026-11-10
tags: [bmad, filament, blueprint, theme, trattoria, folio, frontoffice]
qmd: "trattoria theme filament blueprint folio frontoffice xotbase widgets actions"
related:
  - ./README.md
  - ../customer-flows-contract.md
  - ../../../Modules/Restaurant/docs/bmad/filament-blueprint.md
---

# Filament Blueprint — Trattoria

## Scopo
Trattoria è il tema **frontoffice** del tenant restaurant (Folio/Blade/CSS/JS). Filament è usato solo per componenti/widget/azioni riusabili conformi a XotBase quando previsti, mai per duplicare dominio o logica backoffice.

## Regole
1. **Dominio fuori dal tema**: modelli, validazioni, stati e persistenza in `Modules/Restaurant` (e moduli owner).
2. **XotBase only**: widget/form/action Filament estendono `XotBase*`.
3. **No controller FO**: routing via Folio in `resources/views/pages/`.
4. **Link interni FO**: usare route Folio container (`route('container0.index', ['container0' => 'menu|prenota|ordine'])`), non `url('/...')` hardcoded.
5. **Contratti stabili**: `/`, `/menu`, `/prenota`, `/ordine` rispettano `customer-flows-contract.md`.
6. **UI production-ready**: mobile-first, accessibile (focus, contrasto, target 44px, reduced-motion), asset da `public_html/themes/Trattoria`.

## Integrazione Restaurant
- Menu: `ProductCategory/Product` attivi.
- Prenotazione/ordine: dati compatibili con Action/domain (`Reservation`, `Order`) e modalità `dine_in|takeaway|delivery`.
- Componenti Filament/Livewire delegano alle Action, senza logica business nel Blade.
