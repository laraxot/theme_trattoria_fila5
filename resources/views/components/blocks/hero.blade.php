{{-- Blocco hero CMS. Dati: title, subtitle, actions[{label, route, params, variant}], aside{label, hours, note}. --}}
@php
    $heroActions = $actions ?? [];
    $heroAside = $aside ?? null;
@endphp
<section class="hero">
    <div class="container hero__grid">
        <div class="hero__copy">
            @if (filled($eyebrow ?? null))<p class="eyebrow">{{ $eyebrow }}</p>@endif
            <h1>{{ $title ?? '' }}</h1>
            @if (filled($subtitle ?? null))<p class="hero__lead">{{ $subtitle }}</p>@endif
            @if ($heroActions !== [])
                <div class="actions">
                    @foreach ($heroActions as $action)
                        @php
                            $variant = $action['variant'] ?? 'primary';
                            // Ogni variante ha la sua classe: prima `primary` restava un pulsante senza sfondo.
                            $class = 'button button--'.$variant;
                            $href = isset($action['route']) ? route($action['route'], $action['params'] ?? []) : ($action['url'] ?? '#');
                        @endphp
                        <a class="{{ $class }}" href="{{ $href }}">{{ $action['label'] ?? '' }}</a>
                    @endforeach
                </div>
            @endif
        </div>
        @if (is_array($heroAside))
            <aside class="hero__aside" aria-label="{{ $heroAside['label'] ?? '' }}">
                @if (filled($heroAside['label'] ?? null))<p class="hero__aside-label">{{ $heroAside['label'] }}</p>@endif
                @if (filled($heroAside['hours'] ?? null))<p class="hero__aside-time">{{ $heroAside['hours'] }}</p>@endif
                @if (filled($heroAside['note'] ?? null))<p class="hero__aside-note">{!! nl2br(e($heroAside['note'])) !!}</p>@endif
            </aside>
        @endif
    </div>
</section>
