{{--
    Canali di contatto. Dati: eyebrow?, title?, lead?, channels[{title, text, action_label?, route?, params?, url?,
    mail_from_config?}]. `route` (rotta con nome) o `url` (anche mailto:/tel:); `mail_from_config: true` usa
    l'indirizzo di MAIL_FROM_ADDRESS, ma non lo mostra finche' e' quello d'esempio (example.com).
    Senza link il canale e' solo testo.
--}}
<section class="section">
    <div class="container">
        @if (filled($eyebrow ?? null))<p class="eyebrow">{{ $eyebrow }}</p>@endif
        @if (filled($title ?? null))<h2>{{ $title }}</h2>@endif
        @if (filled($lead ?? null))<p class="lead">{{ $lead }}</p>@endif
        <div class="contact-grid">
            @foreach (($channels ?? []) as $channel)
                @php
                    $href = isset($channel['route']) ? route($channel['route'], $channel['params'] ?? []) : ($channel['url'] ?? null);
                    if ((bool) ($channel['mail_from_config'] ?? false)) {
                        $address = (string) config('mail.from.address');
                        $href = filled($address) && ! str_ends_with($address, 'example.com') ? 'mailto:'.$address : null;
                    }
                @endphp
                <div class="card contact-card">
                    <h3>{{ $channel['title'] ?? '' }}</h3>
                    @if (filled($channel['text'] ?? null))<p>{{ $channel['text'] }}</p>@endif
                    @if (filled($href) && filled($channel['action_label'] ?? null))
                        <a class="button button--ghost" href="{{ $href }}">{{ $channel['action_label'] }}</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
