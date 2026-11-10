{{--
    Selettore lingua del header: dropdown con bandiere, stesso URL nell'altra
    lingua (link da BuildLocaleSwitchAction, modulo Restaurant). `<details>` =
    accessibile senza JS, funziona dentro la nav desktop e mobile.
--}}
@php
    $languageLinks = app(\Modules\Restaurant\Actions\Locale\BuildLocaleSwitchAction::class)->execute(app()->getLocale(), request()->path(), request()->getQueryString());
    $currentLink = collect($languageLinks)->firstWhere('current', true) ?? $languageLinks[0] ?? null;
@endphp
@if ($currentLink !== null)
    <details class="lang-switch">
        <summary class="lang-switch__toggle" aria-label="{{ __('restaurant::public_restaurants.language.switch.label') }}">
            <span class="lang-switch__flag" aria-hidden="true">@svg('lang-flag/'.$currentLink['flag'], 'lang-switch__flag-icon', ['width' => 20, 'height' => 20, 'aria-hidden' => 'true', 'focusable' => 'false'])</span>
            <span class="lang-switch__code">{{ strtoupper($currentLink['locale']) }}</span>
            <span class="lang-switch__chevron" aria-hidden="true">▾</span>
        </summary>
        <ul class="lang-switch__menu">
            @foreach ($languageLinks as $link)
                <li>
                    <a class="lang-switch__item" href="{{ $link['url'] }}" hreflang="{{ $link['locale'] }}" @if ($link['current']) aria-current="true" @endif>
                        <span class="lang-switch__flag" aria-hidden="true">@svg('lang-flag/'.$link['flag'], 'lang-switch__flag-icon', ['width' => 20, 'height' => 20, 'aria-hidden' => 'true', 'focusable' => 'false'])</span>
                        <span class="lang-switch__name">{{ $link['label'] }}</span>
                        @if ($link['current'])<span class="lang-switch__check" aria-hidden="true">✓</span>@endif
                    </a>
                </li>
            @endforeach
        </ul>
    </details>
@endif
