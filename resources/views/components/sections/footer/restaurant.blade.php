{{--
    Footer del sito di un singolo ristorante (`/{lang}/restaurants/{slug}/...`).
    Dati solo del ristorante: nome e link alle sue pagine. Nessun dato generico del tema.
    Variabile: $restaurant (Modules\Restaurant, risolto da ResolveRouteRestaurantAction).
--}}
@php
    $restaurantSlug = (string) $restaurant->getAttribute('slug');
    $restaurantName = (string) $restaurant->getAttribute('name');
    $restaurantParams = ['container0' => 'restaurants', 'slug0' => $restaurantSlug];
    $profile = app(\Modules\Restaurant\Actions\Profile\GetRestaurantProfileAction::class)->execute($restaurant);
@endphp
<footer class="site-footer">
    <div class="container site-footer__grid">
        <div>
            <a class="brand brand--footer" href="{{ route('container0.view', $restaurantParams) }}">
                @if ($profile['logo'] !== null)
                    <img class="brand__logo" src="{{ app(\Modules\Restaurant\Actions\Asset\RestaurantAssetUrlAction::class)->execute($profile['logo']) }}" alt="" width="36" height="36">
                @else
                    <span class="brand__mark" aria-hidden="true">✦</span>
                @endif
                <span><strong>{{ $restaurantName }}</strong><small>{{ $profile['slogan'] ?? __('restaurant::public_restaurants.site.subtitle.text') }}</small></span>
            </a>
            <p class="site-footer__note">{{ __('restaurant::public_restaurants.site.tagline.text') }}</p>
        </div>
        <div>
            <h2 class="footer-heading">{{ __('restaurant::public_restaurants.site.about.label') }}</h2>
            <p><a href="{{ route('container0.view', $restaurantParams) }}">{{ __('restaurant::public_restaurants.card.view_detail.label') }}</a><br>
                <a href="{{ route('container0.container1.view', $restaurantParams + ['container1' => 'menu']) }}">{{ __('pub_theme::nav.menu') }}</a></p>
        </div>
        <div>
            <h2 class="footer-heading">{{ __('restaurant::public_restaurants.site.book_table.label') }}</h2>
            <p><a href="{{ route('container0.container1.view', $restaurantParams + ['container1' => 'book']) }}">{{ __('restaurant::public_restaurants.site.book_table.label') }} →</a></p>
        </div>
        <div>
            <h2 class="footer-heading">{{ __('restaurant::public_restaurants.contact.title.label') }}</h2>
            <p class="site-footer__contacts">
                @if ($profile['address'] !== null){{ $profile['address'] }}<br>@endif
                @if ($profile['phone'] !== null)<a href="tel:{{ preg_replace('/[^+0-9]/', '', $profile['phone']) }}">{{ $profile['phone'] }}</a><br>@endif
                @if ($profile['email'] !== null)<a href="mailto:{{ $profile['email'] }}">{{ $profile['email'] }}</a>@endif
            </p>
        </div>
    </div>
    <div class="container site-footer__bottom">
        <span>© {{ date('Y') }} {{ $restaurantName }}</span>
        <span>{{ __('pub_theme::footer.made_in_italy') }}</span>
    </div>
</footer>
