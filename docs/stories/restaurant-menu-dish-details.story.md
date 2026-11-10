# Story: menu ristorante — badge allergeni colorati e dettaglio piatto

## Richiesta

Il menu di `/it/restaurants/<slug>/menu` deve essere "emozionante": foto, icone e colori per
gli allergeni, dettaglio cliccabile per piatto con ingredienti e 2–3 foto, layout pensato per
cellulare e tablet.

## Fatto

- `Modules\Restaurant\Enums\AllergenEnum`: `code()` (sigla breve) e `color()` (colore di sfondo).
  I colori sono verificati contro il bianco: rapporto di contrasto tra 6,4:1 e 8,9:1 (WCAG AA).
  Il colore non è l'unico segnale: sigla ed etichetta restano visibili.
- `components/blocks/menu/allergen-badge.blade.php`: badge unico per allergeni e regimi,
  con SVG autoregistrati `restaurant-{icon}`, sigla, etichetta e descrizione tooltip.
- `components/blocks/menu/allergen-legend.blade.php`: legenda condivisa tra i blocchi
  del menu, con descrizioni leggibili senza dipendere da colore o tooltip; griglia
  mobile-first e due colonne da 640px.
- `components/blocks/menu/restaurant.blade.php`: ogni piatto è un `<details>` (apribile da
  tastiera e senza JavaScript) con prezzo e badge nel riepilogo, e nel dettaglio descrizione
  e ingredienti, ciascuno con i propri allergeni.
- Layout a griglia: 1 colonna su cellulare, 2 da 640px, 3 da 1024px (`.dish-grid`).
- Verifica HTTP: `cantina-montanari-tbra` e `osteria-del-borgo` in IT ed EN rispondono 200,
  20 piatti resi come dettaglio, 70 badge, nessuna chiave di traduzione grezza, nessuna voce
  nuova nel log.

## Bloccato o non fatto

1. **CSS non compilato.** `npx vite build --config Themes/Trattoria/vite.config.js` fallisce:
   la configurazione PostCSS usa il plugin `tailwindcss` come se fosse v3, mentre il progetto
   usa Tailwind v4 (serve `@tailwindcss/postcss`). Aggiungere una dipendenza richiede
   approvazione. Finché non è risolto, le regole `.dish*` e `.allergen-badge` in
   `resources/css/app.css` non arrivano al sito: il menu appare senza stile dedicato.
2. **Foto dei piatti.** Non esiste nel modello `Product` un campo immagine, e nel repo non ci
   sono foto di piatti. Servono: (a) il materiale fotografico del ristorante; (b) una decisione
   sul salvataggio (colonna JSON additiva o libreria media, che oggi non è in `composer.json`).
   Non inserisco immagini segnaposto né scaricate: la licenza va verificata.
3. **Icone.** Implementate per i 14 allergeni UE e i quattro regimi alimentari nel
   modulo Restaurant. La legenda e i filtri usano lo stesso alias SVG.
4. **Blocco legacy.** `components/blocks/public_menu.blade.php` mantiene una presentazione
   piatti duplicata, ma condivide ora badge e legenda; l'unificazione del rendering resta
   fuori da questo fix.
5. **Test.** Nessun test Pest aggiunto: la connessione `user` del test dipende da `.env`,
   che non posso toccare. Da fare quando il test DB è disponibile.
