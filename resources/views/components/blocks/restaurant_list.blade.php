{{--
    Elenco dei ristoranti pubblici del portale: `/{lang}/restaurants`, la home e `/{lang}/menu`.
    Ogni scheda: logo, nome, slogan, indirizzo e le azioni scheda / menu / prenota.
    Dati facoltativi dal JSON: eyebrow, title, lead, heading ('h1' sulla pagina dedicata, 'h2' dentro
    altre pagine), primary ('menu' | 'book': quale azione evidenziare).
    Dati dei ristoranti: Action del modulo Restaurant; questa vista e' solo presentazione.
--}}
@php
    $restaurants = app(\Modules\Restaurant\Actions\Menu\ListPublicRestaurantsAction::class)->execute();
    $profileAction = app(\Modules\Restaurant\Actions\Profile\GetRestaurantProfileAction::class);
    $assetUrl = app(\Modules\Restaurant\Actions\Asset\RestaurantAssetUrlAction::class);
    $headingTag = ($heading ?? 'h1') === 'h2' ? 'h2' : 'h1';
    $cardHeadingTag = $headingTag === 'h1' ? 'h2' : 'h3';
    $primaryAction = ($primary ?? 'menu') === 'book' ? 'book' : 'menu';
@endphp
<section class="section">
    <div class="container">
        @if (filled($eyebrow ?? null))<p class="eyebrow">{{ $eyebrow }}</p>@endif
        <{{ $headingTag }}>{{ $title ?? __('restaurant::public_restaurants.index.title.text') }}</{{ $headingTag }}>
        <p class="lead">{{ $lead ?? __('restaurant::public_restaurants.index.lead.text') }}</p>

        @if ($restaurants->isEmpty())
            <p class="hint">{{ __('restaurant::public_restaurants.index.empty.text') }}</p>
        @else
            <div class="restaurant-grid">
                @foreach ($restaurants as $restaurant)
                    @php
                        $restaurantSlug = (string) $restaurant->getAttribute('slug');
                        $profile = $profileAction->execute($restaurant);
                        $links = [
                            'detail' => route('container0.view', ['container0' => 'restaurants', 'slug0' => $restaurantSlug]),
                            'menu' => route('container0.container1.view', ['container0' => 'restaurants', 'slug0' => $restaurantSlug, 'container1' => 'menu']),
                            'book' => route('container0.container1.view', ['container0' => 'restaurants', 'slug0' => $restaurantSlug, 'container1' => 'book']),
                        ];
                    @endphp
                    <article class="card restaurant-card">
                        <div class="restaurant-card__head">
                            @if (filled($profile['logo']))
                                <img class="restaurant-card__logo" src="{{ $assetUrl->execute($profile['logo']) }}" alt="" width="56" height="56" loading="lazy">
                            @else
                                <span class="restaurant-card__logo restaurant-card__logo--initial" aria-hidden="true">{{ mb_strtoupper(mb_substr((string) $restaurant->getAttribute('name'), 0, 1)) }}</span>
                            @endif
                            <div>
                                <{{ $cardHeadingTag }} class="restaurant-card__name"><a href="{{ $links['detail'] }}">{{ $restaurant->getAttribute('name') }}</a></{{ $cardHeadingTag }}>
                                @if (filled($profile['slogan']))<p class="restaurant-card__slogan">{{ $profile['slogan'] }}</p>@endif
                            </div>
                        </div>
                        @if (filled($profile['address']))<p class="restaurant-card__address">{{ $profile['address'] }}</p>@endif
                        <div class="actions">
                            <a @class(['button button--small', 'button--primary' => $primaryAction === 'menu', 'button--ghost' => $primaryAction !== 'menu']) href="{{ $links['menu'] }}">{{ __('restaurant::public_restaurants.card.view_menu.label') }}</a>
                            <a @class(['button button--small', 'button--primary' => $primaryAction === 'book', 'button--ghost' => $primaryAction !== 'book']) href="{{ $links['book'] }}">{{ __('restaurant::public_restaurants.card.book.label') }}</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
