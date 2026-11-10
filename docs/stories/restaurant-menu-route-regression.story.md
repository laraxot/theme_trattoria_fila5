# Story: regressione rotta menu ristorante e errore di sintassi allergeni

## Contesto

Il log `laravel/storage/logs/laravel.log` conteneva 8 errori nella finestra 11:59–12:02:

1. `Route [container0.container1.view] not defined` in `restaurant_list.blade.php` e `restaurant_detail.blade.php`.
2. `Undefined variable $item` in `components/blocks/menu/restaurant.blade.php` (x7).
3. `syntax error, unexpected identifier "alimentazione"` in `Modules/Restaurant/lang/it/allergen_enum.php`.

## Causa radice

- Il file Folio `pages/[container0]/[slug0]/[container1]/index.blade.php` è stato riscritto
  per la sola prenotazione: il nome della rotta è diventato `container0.container1.view.book`
  e il ramo menu (che passava `item` al blocco) è sparito. I link di scheda e menu usano il
  nome generico, quindi non trovano più la rotta; il blocco menu riceve `$item` vuoto.
- In `allergen_enum.php` le descrizioni di `vegan` e `vegetarian` contenevano apostrofi non
  escapati dentro stringhe a apice singolo: errore di parsing, quindi il file non si carica.

## Correzione

- Rotta a tre segmenti con nome generico `container0.container1.view`.
- `container1 = menu`: risolve il ristorante con `ResolvePageAction` e passa `item` alla
  pagina (`mountMenu()`). `container1 = book`: invariato.
- Apostrofi escapati (`\'`) nelle righe `vegan` e `vegetarian` di `allergen_enum.php`.

## Verifica

Eseguita via HTTP sul server locale (`127.0.0.1:8000`):

| URL | Esito |
|---|---|
| `/it/restaurants` | 200, 9 ristoranti con link scheda e menu |
| `/it/restaurants/osteria-del-borgo` | 200 |
| `/it/restaurants/osteria-del-borgo/menu` | 200 |
| `/en/restaurants/osteria-del-borgo/menu` | 200 |
| `/it/restaurants/osteria-del-borgo/book` | 200 |
| `/it/restaurants/nope/menu` | 404 |

`php -l` su `allergen_enum.php` e sul file Folio: nessun errore. Log riletto dopo la
correzione: nessuna nuova voce. Log salvato in backup nello scratchpad e svuotato.

Non eseguiti: test Pest (la connessione `user` dipende da `.env`, non modificabile da qui) e
PHPStan/Pint su questi file.

## Note

- `/it/restaurants/nope/book` risponde ancora 200: lo slug del ristorante non viene verificato
  nella pagina prenotazione. Storia separata.
- Il file Folio è stato modificato anche da un'altra sessione (rinomina `$data` → `$pageData`):
  la versione corrente è stata mantenuta.
