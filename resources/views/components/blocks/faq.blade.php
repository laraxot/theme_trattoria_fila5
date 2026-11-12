{{--
    Domande frequenti a fisarmonica (<details>: niente JS, accessibile da tastiera e screen reader).
    Dati: eyebrow?, title?, lead?, groups[{name?, items[{question, answer}]}]. `answer` e' HTML semplice
    (paragrafi, elenchi, link), ripulito da SanitizeHtmlAction.
--}}
@php
    $sanitize = app(\Modules\Cms\Actions\SanitizeHtmlAction::class);
@endphp
<section class="section">
    <div class="container faq">
        @if (filled($eyebrow ?? null))<p class="eyebrow">{{ $eyebrow }}</p>@endif
        @if (filled($title ?? null))<h2>{{ $title }}</h2>@endif
        @if (filled($lead ?? null))<p class="lead">{{ $lead }}</p>@endif
        @foreach (($groups ?? []) as $group)
            <div class="faq__group">
                @if (filled($group['name'] ?? null))<h3 class="faq__group-title">{{ $group['name'] }}</h3>@endif
                @foreach (($group['items'] ?? []) as $item)
                    <details class="faq__item">
                        <summary class="faq__question">{{ $item['question'] ?? '' }}</summary>
                        <div class="faq__answer prose">{!! $sanitize->execute((string) ($item['answer'] ?? '')) !!}</div>
                    </details>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
