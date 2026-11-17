{{--
    Blocco di testo lungo (privacy, cookie, termini, chi siamo). Dati: eyebrow?, title?, lead?, content (HTML),
    updated_at? (data di aggiornamento mostrata in cima, utile per i testi legali).
    L'HTML passa da SanitizeHtmlAction (Cms): restano titoli, paragrafi, elenchi, link e tabelle semplici;
    le tabelle vengono avvolte in un contenitore scorrevole focalizzabile.
--}}
@php
    $html = app(\Modules\Cms\Actions\SanitizeHtmlAction::class)->execute((string) ($content ?? ''));
    // Tabelle larghe: contenitore scorrevole raggiungibile da tastiera (WCAG scrollable-region-focusable).
    $html = str_replace(
        ['<table>', '</table>'],
        ['<div class="table-scroll" tabindex="0" role="region" aria-label="'.e(__('pub_theme::nav.table_region')).'"><table>', '</table></div>'],
        $html,
    );
@endphp
<section class="section">
    <div class="container prose-block">
        @if (filled($eyebrow ?? null))<p class="eyebrow">{{ $eyebrow }}</p>@endif
        @if (filled($title ?? null))<h2>{{ $title }}</h2>@endif
        @if (filled($lead ?? null))<p class="lead">{{ $lead }}</p>@endif
        @if (filled($updated_at ?? null))<p class="prose-block__updated">{{ $updated_at }}</p>@endif
        <div class="prose">{!! $html !!}</div>
    </div>
</section>
