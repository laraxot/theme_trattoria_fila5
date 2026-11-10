{{--
    Elenco dei ristoranti pubblici (`/{lang}/restaurants`). Ogni ristorante ha la sua scheda
    (`/{lang}/restaurants/{slug}`) e il suo menu (`/{lang}/restaurants/{slug}/menu`).
    Dati: Action del modulo Restaurant. Questa vista e' solo presentazione.
--}}
@php
    $restaurants = app(\Modules\Restaurant\Actions\Menu\ListPublicRestaurantsAction::class)->execute();
@endphp
<section class="section">
    <div class="container">
        <h1>{{ __('restaurant::public_restaurants.index.title.text') }}</h1>
        <p class="lead">{{ __('restaurant::public_restaurants.index.lead.text') }}</p>

        @forelse ($restaurants as $restaurant)
            @php($restaurantSlug = (string) $restaurant->getAttribute('slug'))
            <article class="card" style="margin-top:1rem">
                <h2>{{ $restaurant->getAttribute('name') }}</h2>
                <div class="actions">
                    <a class="button button--small button--ghost" href="{{ route('container0.view', ['container0' => 'restaurants', 'slug0' => $restaurantSlug]) }}">{{ __('restaurant::public_restaurants.card.view_detail.label') }}</a>
                    <a class="button button--small button--primary" href="{{ route('container0.container1.view', ['container0' => 'restaurants', 'slug0' => $restaurantSlug, 'container1' => 'menu']) }}">{{ __('restaurant::public_restaurants.card.view_menu.label') }}</a>
                </div>
            </article>
        @empty
            <p class="hint">{{ __('restaurant::public_restaurants.index.empty.text') }}</p>
        @endforelse
    </div>
</section>
