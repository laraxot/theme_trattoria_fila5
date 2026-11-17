---
id: E-TRATTORIA-1.S7
title: Audit integrale del front office (ospite e loggato) per il rilascio
type: story
status: done
epic: E-TRATTORIA-1
priority: P0
created: 2026-11-12
updated: 2026-11-12
repository: https://github.com/festionali/base_restaurant_fila5
related:
  - ../cms-pages-and-blocks.md
  - ../../tests/e2e/README.md
---

# Story E-TRATTORIA-1.S7 — Front office pronto per la produzione

Come gestore della piattaforma voglio che ogni pagina pubblica funzioni, si legga bene e sia accessibile,
per ospiti e utenti loggati, su telefono e desktop, prima di andare in produzione.

## Decisioni (utente, 2026-11-12)

- **Privacy**: ogni ristorante è titolare dei dati dei suoi clienti; la piattaforma è responsabile (art. 28 GDPR)
  e titolare solo per account e sicurezza del sito.
- **Sito pubblico = portale di ristoranti**: home e `/menu` elencano i ristoranti, `/prenota` chiede quale,
  nessun orario o indirizzo inventato.
- **Asporto e consegna**: nascosti ora, da implementare (story separata nel modulo Restaurant).

## Fatto

1. Documento HTML: doctype, `lang`, skip link tradotto, font self-hosted (`@fontsource`), niente Google Fonts.
2. Pagine CMS riscritte per il portale (privacy, cookie, termini, chi siamo, FAQ, contatti, menu, ordine, prenota);
   tolte quelle fuori tema (blog, servizi, elettromedicali); alias SEO `about→chi-siamo`, `contacts→contatti`...
3. Nuovi blocchi `prose`, `faq`, `contact`; `restaurant_list` con logo, slogan, indirizzo, azioni.
4. 404 veri al posto di pagine vuote; pagine di errore con i colori del tema, it/en.
5. Lingue limitate a it/en (`laravellocalization.supportedLocales`): de/es mostravano testi inglesi.
6. Password dimenticata: broker sulla connessione `user` (prima `liveuser_general`, inesistente), layout email del
   tema con ripiego su Notify, logo configurato come URL risolto su disco (`AssetPathAction`), pagine riscritte in
   italiano/inglese, messaggio identico per email esistenti o no, errore d'invio mostrato all'utente.
7. Messaggi del framework in italiano (`laravel/lang/it`: auth, passwords, validation, pagination) con nomi dei campi.
8. `h1` unico su tutte le pagine; tabelle dei testi legali scorrevoli da tastiera; contrasto del pulsante hero.
9. `settings` del ristorante non più codificato due volte (cast `array` del Tenant).

## Verifica

- `tests/e2e/audit.py`: 37 indirizzi × 4 profili (ospite desktop/telefono, cliente telefono, ristoratore desktop):
  0 problemi, 0 link interni rotti (unica voce: la 404 voluta).
- `tests/e2e/flows.py`: 27/27. `tests/e2e/pwreset.py`: reset completo e ripristino.
- Pest Restaurant 152/152; PHPStan max 0 errori sui file toccati.

## Restano fuori (prima del go-live, a carico del gestore)

- Ragione sociale e contatti reali del gestore nei testi legali; `MAIL_FROM_ADDRESS` reale (ora `example.com`).
- Nome del marchio: "Trattoria" è configurabile (`metatag.php`, `nav.brand`); `APP_NAME` compare nelle email.
