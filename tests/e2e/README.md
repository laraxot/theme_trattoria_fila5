# Prove end-to-end del front office (Playwright)

Script che controllano il sito pubblico come lo vede un utente vero: ospite e loggato, desktop e telefono.
Servono un'installazione con i dati demo (`RestaurantDirectorySeeder`) e gli utenti di prova
`ristoratore@example.test`, `cliente@example.test` (password `password`).

| Script | Cosa controlla |
| --- | --- |
| `audit.py <cartella>` | ~37 pagine × 4 profili: HTTP, errori console, chiavi di traduzione grezze, overflow su telefono, immagini, `h1` unico, link interni rotti, accessibilità con axe-core (WCAG 2 A/AA). |
| `flows.py <cartella>` | Flussi completi: tavoli e QR (3 modelli di stampa), ordine dal tavolo con link firmato, interruttore ordini, header con login/avatar, logout. |
| `pwreset.py` | Password dimenticata: richiesta, link nel log delle email (`MAIL_MAILER=log`), nuova password, accesso; poi rimette `password`. |

```bash
pip install playwright && npm install          # axe-core per audit.py
RESTAURANT_BASE_URL=http://restaurant.local:8000 python3 audit.py audit-out
python3 flows.py flows-out
RESTAURANT_LOG=/percorso/laravel/storage/logs/laravel.log python3 pwreset.py
```

`flows.py` usa il client `mysql` per leggere e pulire i dati di prova (tavolo "Terrazza 1").
Ogni problema stampato da `audit.py` va corretto prima di un rilascio; il riepilogo finale raggruppa i problemi per tipo.
