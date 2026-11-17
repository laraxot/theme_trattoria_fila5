{{--
    Accesso nell'header del sito (generale e minisito ristorante).
    - Ospite: link "Accedi" (pagina Folio auth/login).
    - Loggato: avatar con le iniziali; il menu mostra nome ed email, le aree di lavoro a cui l'utente
      puo' entrare (GetUserWorkAreasAction: ristoratore, gestione, sala, cucina...) ed "Esci" (POST con CSRF).
    Stesso schema del selettore lingua: `<details>`, funziona senza JS e da tastiera.
--}}
@auth
    @php
        $authUser = auth()->user();
        $displayName = trim((string) ($authUser->name ?? '')) ?: (string) ($authUser->email ?? '');
        $initials = collect(preg_split('/[\s@._-]+/', $displayName) ?: [])
            ->filter()
            ->take(2)
            ->map(static fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('') ?: '?';
        $workAreas = app(\Modules\Restaurant\Actions\Account\GetUserWorkAreasAction::class)->execute($authUser);
    @endphp
    <details class="user-menu">
        <summary class="user-menu__toggle" aria-label="{{ __('pub_theme::nav.account_menu', ['name' => $displayName]) }}">
            <span class="user-menu__avatar" aria-hidden="true">{{ $initials }}</span>
            <span class="user-menu__chevron" aria-hidden="true">▾</span>
        </summary>
        <div class="user-menu__panel">
            <div class="user-menu__who">
                <strong>{{ $displayName }}</strong>
                @if (filled($authUser->email ?? null) && $authUser->email !== $displayName)
                    <small>{{ $authUser->email }}</small>
                @endif
            </div>
            @if ($workAreas !== [])
                <p class="user-menu__heading">{{ __('pub_theme::nav.work_areas') }}</p>
                <ul class="user-menu__list">
                    @foreach ($workAreas as $area)
                        <li><a class="user-menu__item" href="{{ $area['url'] }}">{{ $area['label'] }}</a></li>
                    @endforeach
                </ul>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="user-menu__logout">
                @csrf
                <button type="submit" class="user-menu__item user-menu__item--button">{{ __('pub_theme::nav.logout') }}</button>
            </form>
        </div>
    </details>
@else
    <a class="user-login" href="{{ route('login') }}">
        <span class="user-login__icon" aria-hidden="true">👤</span>
        <span>{{ __('pub_theme::nav.login') }}</span>
    </a>
@endauth
