# Story: selettore lingua nel header (dropdown con bandiere)

Supersedes: nessuna (story nuova).
Collegata da: `Themes/Trattoria/docs/stories/restaurant-minisite-award-audit.story.md`.
Richiesta utente (verbatim): "nel header manca la scelta della lingua"; poi "le lingue sono tante
percio' meglio mettere un dropdown con le bandiere, studia meglio la ui/ux".

## Causa (perche' non c'era)

Nessun header del tema aveva un selettore: `header/v1.blade.php` e `header/restaurant.blade.php`
mostravano solo brand e navigazione. La lingua viene letta dal primo segmento dell'URL
(`/it/`, `/en/`, `RouteServiceProvider` di Xot), quindi il cambio serve solo come link. Non era
stato costruito perche' nessuna story del tema lo elencava come requisito: la verifica delle
pagine era sui contenuti, non sull'header.

## Decisione UI/UX

- Due lingue oggi, ma l'elenco cresce: un link per lingua non scala nel header. Un dropdown
  (`<details>`) mostra una sola voce compatta (bandiera + codice) e apre la lista con nome nativo.
- `<details>` funziona da tastiera senza JS. Lo script aggiunge solo chiusura con Esc (con
  ritorno del focus sul trigger) e click fuori.
- Target 44 px su trigger e voci. Stato corrente segnato con `aria-current` e un segno di spunta.
- Desktop: il selettore sta all'estremo destro, dopo la navigazione. Mobile: prima del pulsante
  menu, pannello ancorato a destra del trigger.

## Regola di derivazione delle lingue

`BuildLocaleSwitchAction` (modulo Restaurant) offre solo le lingue che soddisfano entrambe le
condizioni:

1. presenti in `laravellocalization.supportedLocales`;
2. con il file `lang/{locale}/public_restaurants.php` nel modulo.

Una lingua senza testi non viene mai proposta, perche' la pagina mostrerebbe chiavi grezze. Oggi
sono offerte `it` ed `en`. `de` ed `es` sono in config ma senza traduzioni pubbliche: non compaiono
finche' il file non esiste. Aggiungere una lingua = aggiungere quel file, nessuna modifica al
partial.

Bandiere: `ui-flags.{code}` dal set `Modules/UI/resources/svg/flags` (`it`, `gb` per l'inglese).

## Fatto

- `Modules/Restaurant/app/Actions/Locale/BuildLocaleSwitchAction.php`: link della stessa pagina
  nell'altra lingua, query compresa. Nessun accesso al database.
- `Themes/Trattoria/resources/views/components/sections/header/language-switch.blade.php`: partial
  incluso da `restaurant.blade.php` e `v1.blade.php`, prima del pulsante menu.
- Chiavi `restaurant::public_restaurants.language.{switch,it,en}.label` in `lang/it` e `lang/en`
  (formato a 5 elementi).
- Stile in `resources/css/app.css`: trigger e voci a 44 px; su desktop `order` posiziona il
  selettore dopo la nav (`@media (min-width: 768px)`); su mobile il pannello del drawer
  (`.site-nav .lang-switch__menu`) resta in flusso, il pannello del header è assoluto.

## Incidente (due versioni in conflitto)

Mentre la story era in corso, il partial su disco è stato riscritto (classi `lang-switch`,
icone `@svg('lang-flag-…')`). Il set `lang` non esiste in blade-icons: ogni pagina con header
rispondeva 500 e il log cresceva (1.5 MB, 9 voci `SvgNotFound` per `flag-it`, `flag-gb`,
`flag-es`, `flag-de`).

Correzione: le chiamate ora usano `@svg('ui-flags.'.$code, …)`. Backup del log:
`/tmp/claude-1000/-var-www--bases-base-restaurant-fila5/3f732a5c-ef29-4c9f-98cd-10b57dba0261/scratchpad/laravel.log.backup-lang-flag-svg`.
Log svuotato dopo la verifica.

Regola da ricordare: il set di un'icona si verifica con `@svg` su una pagina reale prima di dire
"fatto". Un 500 sull'header non compare nei test che non renderizzano il layout.

Il CSS `.lang-menu*` in `app.css` non e' piu' usato dal partial: e' rimasto dalla prima versione
e va rimosso in una pulizia successiva (non fatto qui, per non toccare la versione in uso).

## Verifica (DOM, Chrome)

- `/it/restaurants/bistro-colombo/menu`, `/en/restaurants/bistro-colombo`, `/it`: 200, log vuoto.
- 390 px: trigger 44 px alto; pannello aperto 123–299 px (dentro il viewport); nessun overflow
  orizzontale, aperto o chiuso.
- 1280 px: trigger 1159–1233 px; nav termina a 1143 px; nessun overflow.
- Esc chiude il pannello e riporta il focus sul trigger; click fuori lo chiude.

## Non verificato / da decidere

- Altezza header a 390 px: il selettore va a capo con il brand. Valutare se ridurre il brand su
  mobile.
- Il query string viene normalizzato da Symfony: `senza[]=milk` diventa `senza[0]=milk`. Il filtro
  funziona lo stesso, ma l'URL cambia forma.
- Screenshot non catturabile in questo ambiente: la verifica e' numerica (DOM), non di resa.
- Pulizia del CSS `.lang-menu*` non usato (vedi sopra).
