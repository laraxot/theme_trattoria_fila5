---
id: trattoria-theme-docs
slug: trattoria-theme
title: Documentazione Tema Trattoria
description: Guida completa al tema Trattoria per il progetto ristorante, inclusa palette colori, tipografia, layout FO con Cms head/foot Filament, immagini hero, footer e breakpoint responsive.
document_type: theme_documentation
category: theme
status: active
version: 1.2.0
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
related:
  - ../../../Modules/Cms/docs/bmad/filament-layout-styles-scripts.md
tags: [tema, trattoria, css]
keywords: [tema, colori, tipografia, responsive]
maintainer: opencode
license: MIT
---

# Tema Trattoria

> Sostituisce la copia con conflitti Git non risolti e la bozza palette non verificata. Valori sotto allineati a `resources/css/app.css` e ai Blade in `resources/views/`.

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

Il tema registra il plugin `@tailwindcss/vite` in `vite.config.js`, ma le viste Blade usano classi semantiche in `resources/css/app.css` (`.hero`, `.section`, `.card`, `.button`, …).

## Footer di contenuto vs foot di documento

- Sezione CMS footer: componenti sezione tema
- Fine documento: `<x-cms::foot />` (script Filament/Vite) — non confondere

## Breakpoint responsive

Media query plain in `app.css`, mobile-first: <640 / ≥640 / ≥1024.

## Layout FO e Filament (obbligatorio)

`resources/views/components/layouts/main.blade.php` usa:

- `<x-cms::head />` → `@filamentStyles` (Cms)
- `<x-cms::foot />` → `@livewire('notifications')` + `@filamentScripts` (Cms)

Non rimuovere né duplicare. Contratto: [filament-layout-styles-scripts.md](../../../Modules/Cms/docs/bmad/filament-layout-styles-scripts.md) · [Filament 5 Configuring your layout](https://filamentphp.com/docs/5.x/introduction/installation#configuring-your-layout).

Istruzione utente: obbligatorio `@filamentStyles` in Cms head e `@filamentScripts` in Cms foot; BMAD + ECC + second brain.
