---
id: trattoria-theme-docs
slug: trattoria-theme
title: Documentazione Tema Trattoria
description: Guida completa al tema Trattoria per il progetto ristorante, inclusa palette colori, tipografia, configurazione Tailwind, immagini hero, footer e breakpoint responsive.
document_type: theme_documentation
category: theme
status: draft
version: 1.0.0
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
tags: [tema, trattoria, tailwind, css]
keywords: [tema, colori, tipografia, responsive]
created_at: 2026-09-13
updated_at: 2026-09-13
maintainer: opencode
license: MIT
---

# Tema Trattoria

## Palette colori calda

- **Primario**: #D4A574 (beige caldo)
- **Secondario**: #8B4513 (marrone sella)
- **Sfondo**: #F5E6D3 (beige chiaro)
- **Testo**: #2C1810 (marrone scuro)

## Tipografia

- **Titoli**: Font serif (es. `Playfair Display`, `Merriweather`)
- **Corpo**: Font sans-serif (es. `Inter`, `Helvetica Neue`)

## Configurazione Tailwind

File `tailwind.config.js` esteso per il tema Trattoria:

```js
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: '#D4A574',
        secondary: '#8B4513',
        background: '#F5E6D3',
        text: '#2C1810',
      },
      fontFamily: {
        serif: ['Playfair Display', 'serif'],
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
};
```

## Immagini Hero

Le immagini hero dovrebbero essere ottimizzate per:
- Formato WebP per migliore compressione
- Dimensioni consigliate: 1920x1080px (desktop), 1080x1920px (mobile)
- Overlay semi-trasparente con colore primario al 40% per migliorare leggibilità testo

## Configurazione Footer

- Background: colore secondario #8B4513
- Testo: colore sfondo #F5E6D3
- Link: colore primario #D4A574 al hover
- Padding: 8px mobile, 16px desktop

## Breakpoint responsive

- Mobile: < 640px
- Tablet: 640px - 1024px
- Desktop: > 1024px

Utilizzare le classi Tailwind responsive:
- `sm:` per tablet e sopra
- `lg:` per desktop e sopra
- Nessun prefix per mobile first

## Esempio di utilizzo in Blade

```blade
<div class="bg-background text-text min-h-screen">
  <header class="bg-primary/90 text-white py-4">
    <h1 class="text-2xl font-serif lg:text-4xl">Trattoria Italiana</h1>
  </header>
  
  <main class="p-4 lg:p-8">
    <!-- Contenuto -->
  </main>
  
  <footer class="bg-secondary text-background/90 py-4">
    <p class="text-center">&copy; 2026 Trattoria Italiana</p>
  </footer>
</div>
```