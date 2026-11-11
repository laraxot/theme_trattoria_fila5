@php
    $restaurant = app(\Modules\Restaurant\Actions\Menu\ResolveRouteRestaurantAction::class)->execute(request()->route('container0'), request()->route('slug0'));
@endphp
@if ($restaurant !== null)
    @include('pub_theme::components.sections.header.restaurant', ['restaurant' => $restaurant])
@else
@php
    $block = is_array($blocks ?? null) ? ($blocks[0] ?? null) : null;
    $data = is_object($block) && isset($block->data) && is_array($block->data) ? $block->data : (is_array($block['data'] ?? null) ? $block['data'] : []);
    $items = is_array($data['items'] ?? null) ? $data['items'] : [
        ['label' => __('pub_theme::nav.home'), 'route' => 'home'],
        ['label' => __('pub_theme::nav.menu'), 'route' => 'container0.index', 'params' => ['container0' => 'menu']],
        ['label' => __('pub_theme::nav.book_table'), 'route' => 'container0.index', 'params' => ['container0' => 'prenota']],
        ['label' => __('pub_theme::nav.takeaway'), 'route' => 'container0.index', 'params' => ['container0' => 'ordine', 'mode' => 'takeaway']],
        ['label' => __('pub_theme::nav.table_service'), 'route' => 'container0.index', 'params' => ['container0' => 'ordine', 'mode' => 'dine_in']],
        ['label' => __('pub_theme::nav.order_now'), 'route' => 'container0.index', 'params' => ['container0' => 'ordine'], 'cta' => true],
    ];
    $brand = $data['brand'] ?? __('pub_theme::nav.brand');
    $brandSubtitle = $data['brand_subtitle'] ?? __('pub_theme::nav.subtitle');
@endphp
<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ __('pub_theme::nav.home') }}">
            <span class="brand__mark" aria-hidden="true">✦</span>
            <span><strong>{{ $brand }}</strong><small>{{ $brandSubtitle }}</small></span>
        </a>
        @include('pub_theme::components.sections.header.language-switch')
        @include('pub_theme::components.sections.header.user-menu')
        <button type="button" class="icon-button nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="main-navigation">
            <span class="sr-only">{{ __('pub_theme::nav.open_menu') }}</span><span data-nav-glyph aria-hidden="true">☰</span>
        </button>
        <nav id="main-navigation" class="site-nav" data-nav-menu aria-label="{{ __('pub_theme::nav.main_menu') }}">
            @foreach($items as $item)
                @php
                    $label = $item['label'] ?? '';
                    $params = is_array($item['params'] ?? null) ? $item['params'] : [];
                    $url = $item['url'] ?? (isset($item['route']) ? route($item['route'], $params) : '#');
                    $isCta = (bool) ($item['cta'] ?? false);
                @endphp
                <a @class(['button button--small button--primary' => $isCta]) href="{{ $url }}">{{ $label }}</a>
            @endforeach
        </nav>
    </div>
</header>
@endif
