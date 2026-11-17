{{-- Blocco cards CMS. Dati: title, lead, cards[{title, description, action_label, route, params, url}]. --}}
<section class="section">
    <div class="container">
        @if (filled($eyebrow ?? null))<p class="eyebrow">{{ $eyebrow }}</p>@endif
        @if (filled($title ?? null))<h2>{{ $title }}</h2>@endif
        @if (filled($lead ?? null))<p class="lead">{{ $lead }}</p>@endif
        @if (filled($cards ?? null) && $cards !== [])
            <div class="cards" style="margin-top:1.5rem">
                @foreach ($cards as $card)
                    @php
                        $href = isset($card['route']) ? route($card['route'], $card['params'] ?? []) : ($card['url'] ?? '#');
                    @endphp
                    <a class="card" href="{{ $href }}">
                        <h3>{{ $card['title'] ?? '' }}</h3>
                        @if (filled($card['description'] ?? null))<p>{{ $card['description'] }}</p>@endif
                        @if (filled($card['action_label'] ?? null))<span class="card__action">{{ $card['action_label'] }}</span>@endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
