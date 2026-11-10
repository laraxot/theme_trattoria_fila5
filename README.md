# Trattoria

Tema pubblico del progetto **base_restaurant_fila5**, pensato per clienti e camerieri su smartphone, tablet e desktop.

> **Tema attivo:** `pub_theme=Trattoria` in `config/local/restaurant/xra.php`. La risoluzione passa da `XotData::make()`, che legge il file `xra` del tenant.

## Cosa offre

- Home con le tre modalità di servizio: prenotazione, asporto, servizio al tavolo.
- Menu pubblico che legge `ProductCategory` e `Product` attivi del modulo Restaurant.
- Form di prenotazione collegato ai campi di `Reservation`.
- Scelta della modalità ordine (`dine_in`, `takeaway`, `delivery`) e passaggio al menu.
- Layout responsive con palette italiana calda, tipografia Playfair Display + DM Sans, focus visibile e touch target da 44px.

## Stack

- PHP 8.4+, Laravel 13
- Blade + componenti del tema, pagine Folio
- Vite + Tailwind 4 nel build; le viste usano classi semantiche definite in `resources/css/app.css`
- Service provider basato su `XotBaseServiceProvider`

## Struttura

```text
Trattoria/
├── docs/                 # knowledge base del tema
├── resources/
│   ├── css/app.css       # palette, componenti e media query
│   ├── js/app.js         # toggle navigazione mobile
│   └── views/
│       ├── components/   # layout, header, footer, card, form
│       └── pages/        # index, menu, prenota, ordine
├── src/TrattoriaServiceProvider.php
└── vite.config.js
```

## Documentazione

- [Indice knowledge base](./docs/README.md)
- [Inventario documenti](./docs/00-INDEX.md)
- [Theme e stile verificato](./docs/theme.md)
- [Contratti dei flussi cliente](./docs/customer-flows-contract.md)
- [Integrazione navigazione](./docs/navigation-integration.md)
- [Story BMAD](./docs/stories/)

## Sviluppo

Il build del tema usa le dipendenze workspace installate da `laravel/package.json`:

```bash
cd laravel/Themes/Trattoria
npx vite build
```

Il provider pubblica `public/` in `public/themes/trattoria` con il tag `public`. Nel progetto gli asset runtime sono serviti da `public_html/themes/Trattoria` tramite symlink.

## Limiti attuali

- Le pagine pubbliche sono contratti UI: il collegamento alle Action di dominio Restaurant è tracciato nelle story BMAD.
- Il tema non duplica modelli, validazione o logica di dominio.
- `theme.json` non è presente; le informazioni di tema sono in questo README e in `composer.json`.
