# Story: minisito ristorante, audit e primo passo di composizione

Supersedes: nessuna (story nuova; le story esistenti non vengono toccate).
Collegata da: `Themes/Trattoria/docs/stories/` e `Modules/Restaurant/docs/public-restaurant-profile.md`.
Richiesta utente (verbatim): "crea un piano con bmad + ecc, implementa, controllo anche con strumenti visuali, ricorda che deve vedersi bene anche da tablet e da mobile".

## Richiesta

Il minisito `/{lang}/restaurants/{slug}` deve essere un sito vero e proprio: contatti, orari,
menu, prenotazione, ben visibili da tablet e mobile, con una composizione che regga il confronto
con i siti di ristorante premiati. Lo studio di riferimento è la lista fornita dall'utente
(Awwwards hotel-restaurant e food-drink, raccolte di siti di ristorante, guida Michelin).

## Stato verificato

Verifica fatta nel browser con lo slug reale `cantina-lombardi`
(lo slug `cantina-lombardi-upgb` non esiste e dà 404 corretto).

- Mobile 390 px, prima: hero con eyebrow sopra l'H1, card annidate per orari e contatti,
  footer con tagline attaccato al brand. Contenuti presenti, composizione generica.
- Mobile 390 px, dopo: hero con foto del piatto, nessun eyebrow, pannelli con filetto
  superiore invece di card annidate. Screenshot in `scratchpad/minisite-mobile-after.png`.
- Desktop 1280 px, misurato nel DOM: due colonne da 590 px, zero card, zero eyebrow, nessun
  overflow orizzontale. Lo screenshot non si cattura: il browser va in timeout su
  `Page.captureScreenshot` (tre tentativi, anche in viewport). La verifica è quindi numerica.
- Tablet 768 px, misurato nel DOM: colonna singola a 705 px, pulsanti da 48 px (soglia tocco),
  H1 a 46 px, nessun overflow orizzontale.
- Footer: tagline a 12 px dal brand dopo il fix. Causa: una regola `.site-footer p` con
  specificità superiore annullava `.site-footer__note`; il selettore ora è
  `.site-footer .site-footer__note`.

## Fatto in questo passo

- `Themes/Trattoria/resources/views/components/blocks/restaurant_detail.blade.php`: rimosso
  l'eyebrow sopra l'H1 (vietato dal craft floor, nessun brief lo riabilita); le sezioni orari e
  contatti usano `restaurant-home__panel` invece di `card` annidata.
- `Themes/Trattoria/resources/css/app.css`: regole `.restaurant-home__panel` (filetto superiore,
  nessun bordo laterale) e `.site-footer__note`. Bundle ricostruito con `npx vite build`.
- Verifica HTTP: 8000 e 8001 rispondono 200; nessun eyebrow nell'HTML; log vuoto.

## Non fatto (aperto)

1. ~~**Footer:** tagline attaccato al brand~~ — risolto (vedi "Stato verificato").
2. **Email malformata** `hello@cantina-lombardi-upgb@example.com` visibile in pagina: dato, non
   CSS. Serve conferma prima di scrivere sul database (vedi
   `Modules/Restaurant/docs/public-restaurant-profile.md`).
3. **Sezione "Il ristorante"** (chi siamo, foto della sala): assente. Richiede contenuto reale
   del ristorante, non testo inventato.
4. **Titolo "— la tua tabla":** copy di prodotto da confermare con il cliente.
5. **Studio dei siti premiati:** parziale. Due siti osservati (impastofornoantico.com,
   kingriver.com.au), risultati in `Themes/Trattoria/docs/award-benchmark-audit.md`. Gli altri
   della lista restano da aprire uno per uno; nessuna pratica va citata come "vincente" senza
   averla osservata nel browser.
6. ~~**Link "Contatti" rotto**~~ — risolto: il pannello contatti ora ha `id="contatti"`.

## Collegamenti

- Contratto dati: `laravel/Modules/Restaurant/docs/public-restaurant-profile.md`
- Layout e blocchi del tema: `laravel/Themes/Trattoria/docs/layouts.md`
- Story precedente sugli allergeni: `laravel/Modules/Restaurant/docs/stories/allergen-icons-legend.story.md`
