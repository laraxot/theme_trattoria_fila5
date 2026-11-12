<?php

declare(strict_types=1);

return [
    'fields' => [
        'name' => 'Nome e cognome',
        'email' => 'Email',
        'password' => 'Password',
        'passwordConfirmation' => 'Ripeti la password',
        'new_password' => 'Nuova password',
        'password_hint' => 'Almeno 8 caratteri.',
        'remember' => 'Ricordami su questo dispositivo',
    ],
    'login' => [
        'eyebrow' => 'Area personale',
        'title' => 'Accedi',
        'lead' => 'Clienti, ristoratori e personale: entra con la tua email.',
        'submit' => 'Accedi',
        'forgot' => 'Password dimenticata?',
        'register' => 'Non hai un account? Registrati',
    ],
    'register' => [
        'eyebrow' => 'Area personale',
        'title' => 'Crea il tuo account',
        'lead' => 'Ti serve per ritrovare prenotazioni e ordini.',
        'submit' => 'Registrati',
        'login' => 'Hai già un account? Accedi',
    ],
    'back_to_login' => 'Torna all\'accesso',
    'forgot' => [
        'title' => 'Password dimenticata',
        'lead' => 'Scrivi l\'email del tuo account: ti mandiamo un link per sceglierne una nuova.',
        'submit' => 'Inviami il link',
        'sent' => 'Se l\'indirizzo è registrato, tra poco riceverai un\'email con il link. Controlla anche lo spam. Il link vale 60 minuti.',
        'failed' => 'Non siamo riusciti a inviare l\'email. Riprova tra qualche minuto.',
    ],
    'reset' => [
        'title' => 'Scegli una nuova password',
        'lead' => 'Dopo il salvataggio entri subito nel tuo account.',
        'submit' => 'Salva la nuova password',
    ],
    'confirm' => [
        'title' => 'Conferma la password',
        'lead' => 'Per continuare con un\'operazione delicata, inserisci di nuovo la tua password.',
        'submit' => 'Conferma',
    ],
    'verify' => [
        'title' => 'Verifica la tua email',
        'lead' => 'Ti abbiamo mandato un link di conferma. Aprilo per attivare l\'account.',
        'submit' => 'Rimandami il link',
        'resent' => 'Ti abbiamo mandato un nuovo link.',
        'logout' => 'Esci',
    ],
];
