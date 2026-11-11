{{--
    Header del minisito ristorante (`/{lang}/restaurants/{slug}/...`).
    Brand e navigazione restano nel contesto del singolo ristorante.
--}}
@php
    $restaurantSlug = (string) $restaurant->getAttribute('slug');
    $restaurantName = (string) $restaurant->getAttribute('name');
    $restaurantParams = ['container0' => 'restaurants', 'slug0' => $restaurantSlug];
    $profile = app(\Modules\Restaurant\Actions\Profile\GetRestaurantProfileAction::class)->execute($restaurant);
    $items = [
        ['label' => __('restaurant::public_restaurants.site.about.label'), 'url' => route('container0.view', $restaurantParams)],
        ['label' => __('pub_theme::nav.menu'), 'url' => route('container0.container1.view', $restaurantParams + ['container1' => 'menu'])],
        ['label' => __('restaurant::public_restaurants.contact.title.label'), 'url' => route('container0.view', $restaurantParams).'#contatti'],
        ['label' => __('restaurant::public_restaurants.site.book_table.label'), 'url' => route('container0.container1.view', $restaurantParams + ['container1' => 'book']), 'cta' => true],
    ];
@endphp
<header class="site-header site-header--restaurant" data-site-header>
    <div class="container site-header__inner">
        <a href="{{ route('container0.view', $restaurantParams) }}" class="brand" aria-label="{{ $restaurantName }}">
            @if ($profile['logo'] !== null)
                <img class="brand__logo" src="{{ app(\Modules\Restaurant\Actions\Asset\RestaurantAssetUrlAction::class)->execute($profile['logo']) }}" alt="" width="32" height="32">
            @else
                <span class="brand__mark" aria-hidden="true">✦</span>
            @endif
            <span><strong>{{ $restaurantName }}</strong><small>{{ $profile['slogan'] ?? __('restaurant::public_restaurants.site.subtitle.text') }}</small></span>
        </a>
        @include('pub_theme::components.sections.header.language-switch')
        @include('pub_theme::components.sections.header.user-menu')
        <button type="button" class="icon-button nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="main-navigation">
            <span class="sr-only">{{ __('pub_theme::nav.open_menu') }}</span><span data-nav-glyph aria-hidden="true">☰</span>
        </button>
        <nav id="main-navigation" class="site-nav" data-nav-menu aria-label="{{ __('pub_theme::nav.main_menu') }}">
            @foreach ($items as $item)
                <a @class(['button button--small button--primary' => $item['cta'] ?? false]) href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </div>
</header>
