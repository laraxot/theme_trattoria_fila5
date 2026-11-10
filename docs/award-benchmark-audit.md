# Audit di riferimento: siti premiati di ristoranti e food

Supersedes: nessuno (documento nuovo).
Collegato da: `Themes/Trattoria/docs/stories/restaurant-minisite-award-audit.story.md`.
Richiesta utente (verbatim): "aggiorna studia migliora e organizza meglio le cartelle docs dentro i moduli e dentro i temi".

Scopo: capire cosa hanno in comune i siti premiati e confrontarlo con il minisito Trattoria.
Osservazione fatta nel browser, via DOM (non via screenshot: la cattura va in timeout in questo
ambiente). Il campione è piccolo: due siti. Non autorizza a parlare di una "formula vincente".

## Campione

| Sito | Settore | Premio | Note osservate |
|------|---------|--------|----------------|
| impastofornoantico.com | panificio artigianale | Awwwards Nominee (food-drink) | 9 sezioni, 9 immagini, font custom (`ff-scala`, `aaux-next`), navigazione con "Contact", contatti e orari nel contenuto, CTA di prenotazione o vendita presente |
| kingriver.com.au | carne wagyu | Awwwards Nominee (food-drink) | 11 sezioni, 5 immagini, font custom (`sweet-sans-pro`, `feijoa`), navigazione con "Contact", nessun orario (sito B2B) |

Il listato di partenza è `https://www.awwwards.com/websites/food-drink/`. Gli altri siti della lista
(Michelin, Elias, Gotenzo, Framer, Webflow, Pinterest, eccetera) non sono stati aperti: restano da
studiare uno per uno.

## Cosa emerge

1. **Contatto nella navigazione principale.** Entrambi mettono "Contact" nel menu. Il minisito
   lo aveva già nel header (`#contatti`), ma il pannello contatti non aveva l'`id`: il link era
   rotto. Corretto aggiungendo `id="contatti"` al pannello in
   `Themes/Trattoria/resources/views/components/blocks/restaurant_detail.blade.php`.
2. **Tipografia di marca.** Entrambi usano font custom, non di sistema. Il minisito usa Playfair
   Display e DM Sans: due font Google, non di sistema, ma non un carattere di marca. Da valutare
   con il cliente, non da cambiare senza il suo materiale.
3. **Orari e contatti nel contenuto, non solo nel footer.** Il sito del forno li mette nel corpo
   della pagina. Il minisito ora li mette in pannelli nel corpo, con il footer che li ripete.

## Limiti

- Nessuno dei due siti è un ristorante con menu e prenotazione: il confronto è di composizione
  e di navigazione, non di funzione.
- Nessuno screenshot è stato catturato: la verifica visiva è numerica (DOM), non di resa.

## Collegamenti

- Story: `Themes/Trattoria/docs/stories/restaurant-minisite-award-audit.story.md`
- Contratto dati: `laravel/Modules/Restaurant/docs/public-restaurant-profile.md`
