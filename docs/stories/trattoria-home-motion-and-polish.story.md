---
id: trattoria-home-motion-and-polish
title: "Trattoria: movimento dell'hero e pulizia della home /it"
type: story
document_type: story
category: bmad-story
status: done
module: Trattoria
tags: [motion, accessibility, homepage, impeccable, theme]
created: "2026-11-10"
updated: "2026-11-10"
issues: []
discussions: []
---

# Trattoria: movimento dell'hero e pulizia della home /it

## Scopo

Dare alla home guest di Trattoria un solo momento d'ingresso costruito, rimuovere
il rumore decorativo vietato dal craft-floor di Impeccable e correggere difetti
visibili nel render (CTA senza sfondo, immagine dell'hero non caricata, nav in inglese).

## Motion thesis

- **Momento focale:** il titolo dell'hero si srotola dall'alto (clip-path, 800 ms, ease-out esponenziale), come un menu che si apre.
- **Supporto:** il sottotitolo e i CTA compaiono in dissolvenza di sola opacità (600 ms, ritardo 450 ms).
- **Feedback:** `.button:active` e `.mode-card:active` scalano a 0.98 in 100 ms.
- **Budget:** solo transform, opacity e clip-path, una volta sola al caricamento. Nessun loop.

## Fatto

- `resources/css/app.css`: keyframes `hero-title-unroll` e `hero-fade`; regole di feedback; `prefers-reduced-motion` con `animation: none` (contenuto visibile subito, nessuna perdita di stato).
- `resources/css/app.css`: immagine dell'hero puntava a `/themes/trattoria/...` (minuscolo); la cartella pubblica è `Trattoria`. Corretto (404 → 200).
- `resources/css/app.css`: "cucina italiana" del brand da 10.4px a 11.2px e colore del tema; override per il footer (contrasto 2.3:1 → corretto).
- `resources/css/app.css`: `text-wrap: balance` su titoli, `pretty` su paragrafi (parole orfane).
- `resources/views/pages/index.blade.php`: rimossi eyebrow sopra titoli e numerazione 01/02/03 (vietati dal craft-floor); CTA primario con `button--primary`; CTA secondario con classe `button--ghost` al posto dello stile inline.
- `resources/views/components/navigation.blade.php`: link alla home via `route('home')`. La label "Home" → "Inizio" è stata poi riportata a "Home" da una modifica esterna al file; non è stata reintrodotta. Decisione sulla label: a carico del titolare.
- Link interni della home e della nav via `route('container0.index', …)`, vedi [STORY-TRATTORIA-06](STORY-TRATTORIA-06-container0-named-routes.md).

## Verifica

- Build del tema eseguita dalla cartella del tema (`vite build --config vite.config.js`), manifest aggiornato a `app-r6hDarV-.css`.
- Detector Impeccable su `/it`: 0 segnalazioni a 390x844 e 1280x800 (prima: 5 e 8).
- Screenshot 390x844 e 1280x800: CTA primario visibile, immagine hero caricata, niente eyebrow.
- Reduced motion (Playwright, `reducedMotion: 'reduce'`): opacità del paragrafo 1, clip-path del titolo `none`.
- Frame a 350 ms: titolo già visibile, CTA ancora nascosti. Tempi precisi non misurati.

## Aperto

- Le pagine `menu`, `ordine`, `prenota` mantengono eyebrow e copy invariati: fuori scope di questa story.
- Footer con "© 2026 Laravel" e indirizzo "Via della Tavola, 12": contenuto segnaposto, da confermare con il titolare.
- Su `http://127.0.0.1:8000/it` il sito renderizza TwentyOne: `.env` ha `APP_URL=http://localhost:8000` (riscritto esternamente), quindi il tenant non è `restaurant`. Verifica Trattoria su porta 8002 con `APP_URL` impostata nell'ambiente del processo. Decisione sul `.env` spetta all'utente.
