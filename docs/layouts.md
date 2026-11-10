# Layout del tema Trattoria

Documento verificato sul codice in `resources/views/components/layouts/` e
`resources/views/components/sections/`. Sostituisce la versione precedente, che descriveva un
layout `layouts.restaurant_detail` che non esiste più (la cartella `resources/views/layouts/`
è vuota).

## Componenti layout (fonte di verità)

| Componente | File | Ruolo |
|------------|------|-------|
| `<x-layouts.main>` | `components/layouts/main.blade.php` | Shell completa: `<x-cms::head>`, `<x-section slug="header" />`, `<main class="main">`, `<x-section slug="footer" />`, `<x-cms::foot />`. |
| `<x-layouts.app>` | `components/layouts/app.blade.php` | Wrapper che passa `title`, `description`, `theme-color` a `layouts.main`. |
| `<x-layouts.marketing>` | `components/layouts/marketing.blade.php` | Alias di `layouts.main`. |
| `<x-layouts.app1>` | `components/layouts/app1.blade.php` | Alias di `layouts.main`. |

Regola: le pagine usano `layouts.app` (o `main`). Non scrivere `<head>` o `<html>` nelle pagine:
l'head arriva da `<x-cms::head>` in `main`.

## Header e footer per ristorante

Non esiste un layout dedicato ai ristoranti. Header e footer decidono da soli il contesto, leggendo
la rotta:

- `components/sections/header/v1.blade.php` e `footer/v1.blade.php` chiamano
  `ResolveRouteRestaurantAction` con i parametri di rotta `container0` e `slug0`.
- Se la rotta è `restaurants/{slug}` (o una sua sottopagina), includono
  `sections/header/restaurant.blade.php` e `sections/footer/restaurant.blade.php`, con nome,
  link e CTA del ristorante.
- Altrimenti includono l'header e il footer generici del tema.

Questo è il motivo per cui una sottopagina di un ristorante mostra il brand del ristorante.
Il branch sta nella sezione, non in un layout: aggiungere un layout per ristorante duplicherebbe
la shell.

## Pagine e rotte

Le pagine sono file Folio sotto `resources/views/pages/`:

- `[container0]/[slug0]/index.blade.php`: pagina del ristorante (elenco e dettaglio).
- `[container0]/[slug0]/[container1]/index.blade.php`: sottopagine (menu, prenotazione).

I nomi delle route sono generati da Folio e non vanno scritti a mano nei documenti. Per
verificarli: `php artisan route:list --name=container0`.

## Regole per chi modifica i layout

1. Non creare `layouts/restaurant_detail.blade.php`: il branch per-ristorante è già in header e footer.
2. Non usare `<x-header>` o `<x-footer>` del tema per le sezioni: il nome collide con i componenti
   Flux (`header.blade.php` in Flux riceve un array e fallisce con `trim(): Argument #1`).
3. Un blocco CMS che vuole un proprio header non lo ridefinisce: l'header è una sezione CMS.

## Documenti collegati

- [layout-cms-sections.md](layout-cms-sections.md): contratto delle sezioni CMS e namespace `pub_theme`.
- [navigation-integration.md](navigation-integration.md): navigazione e link.
- [cms-pages-and-blocks.md](cms-pages-and-blocks.md): pagine CMS e blocchi.
