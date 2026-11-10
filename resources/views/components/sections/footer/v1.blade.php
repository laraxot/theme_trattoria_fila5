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
    $address = $data['address'] ?? config('app.restaurant_address', 'Via della Tavola, 12');
    $city = $data['city'] ?? config('app.restaurant_city', 'Roma');
    $hours = $data['hours'] ?? __('pub_theme::footer.hours_value');
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
        <div>
            <h2 class="footer-heading">{{ __('pub_theme::footer.visit_us') }}</h2>
            <p>{{ $address }}<br>{{ $city }}</p>
        </div>
        <div>
            <h2 class="footer-heading">{{ __('pub_theme::footer.hours') }}</h2>
            <p>{{ $hours }}<br><a href="{{ route('container0.index', ['container0' => 'prenota']) }}">{{ __('pub_theme::footer.book_table') }} →</a></p>
        </div>
    </div>
    <div class="container site-footer__bottom">
        <span>© {{ date('Y') }} {{ config('app.name', 'Trattoria') }}</span>
        <span>{{ __('pub_theme::footer.made_in_italy') }}</span>
    </div>
</footer>
@endif
