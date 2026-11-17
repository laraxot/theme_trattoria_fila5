@php
    $restaurant = app(\Modules\Restaurant\Actions\Menu\ResolveRouteRestaurantAction::class)->execute(request()->route('container0'), request()->route('slug0'));
@endphp
@if ($restaurant !== null)
    @include('pub_theme::components.sections.footer.restaurant', ['restaurant' => $restaurant])
@else
@php
    $block = is_array($blocks ?? null) ? ($blocks[0] ?? null) : null;
    $data = is_object($block) && isset($block->data) && is_array($block->data) ? $block->data : (is_array($block['data'] ?? null) ? $block['data'] : []);
    $brand = $data['brand'] ?? __('pub_theme::nav.brand');
    $brandSubtitle = $data['brand_subtitle'] ?? __('pub_theme::nav.subtitle');
    $tagline = $data['tagline'] ?? __('pub_theme::footer.tagline');
    // Colonne di link (Esplora, Informazioni, Note legali): dalla sezione CMS `footer`.
    // Niente indirizzo o orari qui: il portale ha piu' ristoranti, ognuno con la sua scheda.
    $columns = is_array($data['columns'] ?? null) ? $data['columns'] : [];
@endphp
<footer class="site-footer">
    <div class="container site-footer__grid">
        <div>
            <a class="brand brand--footer" href="{{ route('home') }}">
                <span class="brand__mark" aria-hidden="true">✦</span>
                <span><strong>{{ $brand }}</strong><small>{{ $brandSubtitle }}</small></span>
            </a>
            <p class="site-footer__note">{{ $tagline }}</p>
        </div>
        @foreach ($columns as $column)
            <nav aria-label="{{ $column['title'] ?? '' }}">
                <h2 class="footer-heading">{{ $column['title'] ?? '' }}</h2>
                <ul class="footer-links">
                    @foreach (($column['links'] ?? []) as $link)
                        @php($href = isset($link['route']) ? route($link['route'], $link['params'] ?? []) : ($link['url'] ?? '#'))
                        <li><a href="{{ $href }}">{{ $link['label'] ?? '' }}</a></li>
                    @endforeach
                </ul>
            </nav>
        @endforeach
    </div>
    <div class="container site-footer__bottom">
        <span>© {{ date('Y') }} {{ $brand }}</span>
        <span>{{ __('pub_theme::footer.made_in_italy') }}</span>
    </div>
</footer>
@endif
