# Layout e sezioni CMS — Trattoria

## Allergen Icons & Legend Implementation

The allergen implementation follows Blade Icons conventions with pre-registered SVG icons in `laravel/Modules/Restaurant/resources/svg/`. Each allergen has:

- **Unique icon name**: kebab-case matching SVG file (e.g., `fish.svg` → `fish`)
- **Blade icon registration**: `@svg('restaurant-'.$case->icon(), 'allergen-icon', ['width' => 14, 'height' => 14])`
- **Color coding**: WCAG AA compliant hex colors from `AllergenEnum::color()` (WCAG AA >= 4.5 with white text)
- **Legend structure**: 
  - Each item in `<li>` with `title="{{ $case->getLabel() }}"` for tooltip
  - `allergen-badge` with `aria-hidden="true"` for icon
  - `allergen-badge__code` with `title="{{ $case->getLabel() }}"` for tooltip
  - `allergen-legend__name` with `getDescription()` for accessibility
- **Mobile requirements**: touch target ≥48px, icons spaced ≥12px from text, responsive grid layout
- **Accessibility**: WCAG 1.4.1 compliance with icon + code + label all present

**Icon file structure**:
- All 18 icons pre-registered in `/laravel/Modules/Restaurant/resources/svg/`
- File naming: kebab-case matching enum icon() method
- Examples: `fish.svg` → `fish`, `vegan.svg` → `vegan`, `kosher.svg` → `kosher`
- Blade icon registration in module service provider

**Implementation notes**:
- Icons are pre-registered via `registerViewComponents` in Restaurant module
- No inline SVG - always use blade-icons `@svg()` helper
- Icons must pass WCAG contrast check (tested with white text on colored badge)
- Mobile: touch targets ≥48px, icons spaced ≥12px from text
- Legend items: 48px height, 16px padding, icon 14x14px, text 16px