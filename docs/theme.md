---
id: trattoria-theme-docs
slug: trattoria-theme
title: Documentazione Tema Trattoria
description: Guida completa al tema Trattoria per il progetto ristorante, inclusa palette colori, tipografia, configurazione Tailwind, immagini hero, footer e breakpoint responsive.
document_type: theme_documentation
category: theme
status: active
version: 1.1.0
language: it
project: base_restaurant_fila5
ecosystem: laravel
framework: laravel
domain: restaurant
priority: high
source_of_truth: docs
scope: theme
audience: developers, designers
execution_mode: manual
destructive_operations_allowed: false
git_strategy: merge
depends_on: []
related: []
tags: [tema, trattoria, css]
keywords: [tema, colori, tipografia, responsive]
created_at: 2026-09-13
updated_at: 2026-09-17
maintainer: opencode
license: MIT
---

# Tema Trattoria

> Aggiornato il 2026-09-17: la versione precedente di questa pagina descriveva
> una palette e una configurazione Tailwind che non corrispondono al codice
> realmente presente in `resources/`. I valori sotto sono verificati contro
> `resources/css/app.css` e i componenti Blade in
> `resources/views/components/`.

## Palette colori (custom properties in `resources/css/app.css`)

- **`--terra`**: `#7f321d` — colore primario (bottoni, link attivi, testo su sfondo chiaro)
- **`--terra-dark`**: `#572313` — footer, titoli
- **`--saffron`**: `#d7954b` — accento (badge, hover, heading footer)
- **`--cream`**: `#fbf5ec` — sfondo header/hero
- **`--paper`**: `#fffdf9` — sfondo card
- **`--ink`**: `#2d211a` — testo principale
- **`--muted`**: `#74655a` — testo secondario
- **`--line`**: `#eaded0` — bordi

## Tipografia

Import Google Fonts dichiarato in cima a `resources/css/app.css`:

- **Titoli** (`h1`, `h2`, `h3`): `Playfair Display`, serif
- **Corpo**: `DM Sans`, con fallback `system-ui, sans-serif`

## Stile CSS: classi semantiche, non utility Tailwind

Il tema registra il plugin `@tailwindcss/vite` in `vite.config.js` (build
pipeline disponibile), ma le viste Blade in `resources/views` **non** usano
classi utility Tailwind: usano classi semantiche scritte a mano in
`resources/css/app.css`, ad esempio `.hero`, `.section`, `.card`,
`.menu-card`, `.mode-card`, `.form-card`, `.field`, `.button`,
`.button--primary`/`.button--secondary`/`.button--ghost`. Prima di introdurre
una nuova classe, verificare se esiste già in `app.css`.

Esempio reale (da `resources/views/pages/index.blade.php`):

```blade
<section class="hero">
  <div class="container">
    <p class="eyebrow">Cucina italiana contemporanea</p>
    <h1>Il gusto di stare insieme.</h1>
    <div class="actions">
      <a class="button" href="{{ url('/menu') }}">Scopri il menu</a>
      <a class="button ghost" href="{{ url('/prenota') }}">Prenota un tavolo</a>
    </div>
  </div>
</section>
```

## Immagine Hero

L'hero attuale è `public/images/hero.svg`, richiamata da CSS come sfondo con
overlay in gradiente:

```css
.hero {
  background: linear-gradient(110deg, rgba(87,35,19,.94), rgba(127,50,29,.76)),
    url('/themes/trattoria/images/hero.svg') center/cover;
}
```

## Footer

Componente: `resources/views/components/footer.blade.php`.

- Sfondo: `var(--terra-dark)` (`#572313`)
- Testo: `#f9ead8` / `#ead8c7`
- Heading (`.footer-heading`): `var(--saffron)`

## Breakpoint responsive

Definiti come media query CSS "plain" in `app.css` (non prefissi Tailwind
`sm:`/`lg:`), mobile-first:

- Mobile: default, < 640px
- Tablet: `@media (min-width: 640px)`
- Desktop: `@media (min-width: 1024px)`

## Layout condiviso

Il layout base è `resources/views/components/layouts/app.blade.php`
(`<x-layouts.app>`), che carica `@vite(['resources/css/app.css',
'resources/js/app.js'], 'themes/Trattoria')` e include `<x-navigation />` e
`<x-footer />`. Il toggle del menu mobile è gestito da
`resources/js/app.js` tramite gli attributi `data-nav-toggle`/`data-nav-menu`.