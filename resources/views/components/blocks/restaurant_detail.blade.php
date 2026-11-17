{{--
    Minisito del ristorante (`/{lang}/restaurants/{slug}`): hero, orari, contatti, CTA.
    Blocco CMS renderizzato dentro <x-page> (layout gia' applicato dal guscio Folio):
    niente @extends, niente layout annidati, niente componenti header/footer.
    Header/footer "microsito ristorante" sono gestiti dai componenti section.
    Dati: $item risolto da ResolvePageAction oppure slug0 dalla rotta;
    contatti/orari da GetRestaurantProfileAction (tenant columns + settings.opening_hours).
--}}
@php
    $restaurant = $item ?? app(\Modules\Restaurant\Actions\Menu\FindPublicRestaurantAction::class)->execute((string) request()->route('slug0', ''));
    $profile = app(\Modules\Restaurant\Actions\Profile\GetRestaurantProfileAction::class)->execute($restaurant);
    $restaurantSlug = (string) $restaurant->getAttribute('slug');
    $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
@endphp
<section class="section restaurant-home">
    <div class="container">
        <header class="menu-hero">
            <h1>{{ __('restaurant::public_restaurants.detail.title.text', ['name' => $restaurant->getAttribute('name')]) }}</h1>
            <p class="lead">{{ __('restaurant::public_restaurants.detail.lead.text') }}</p>
            <div class="actions">
                <a class="button button--primary" href="{{ route('container0.container1.view', ['container0' => 'restaurants', 'slug0' => $restaurantSlug, 'container1' => 'menu']) }}">{{ __('restaurant::public_restaurants.detail.menu_cta.label') }}</a>
                <a class="button button--ghost" href="{{ route('container0.container1.view', ['container0' => 'restaurants', 'slug0' => $restaurantSlug, 'container1' => 'book']) }}">{{ __('restaurant::public_restaurants.detail.book_cta.label') }}</a>
            </div>
        </header>

        <div class="restaurant-home__grid">
            @if ($profile['opening_hours'] !== [])
                <section class="restaurant-home__panel" aria-labelledby="hours-title">
                    <h2 id="hours-title" class="restaurant-home__card-title">{{ __('restaurant::public_restaurants.hours.title.label') }}</h2>
                    <ul class="hours-list">
                        @foreach ($days as $day)
                            @php($entry = $profile['opening_hours'][$day] ?? null)
                            @if ($entry !== null)
                                <li class="hours-list__row">
                                    <span class="hours-list__day">{{ __('restaurant::public_restaurants.hours.'.$day.'.label') }}</span>
                                    @if ($entry['closed'])
                                        <span class="hours-list__closed">{{ __('restaurant::public_restaurants.hours.closed.label') }}</span>
                                    @else
                                        <span class="hours-list__ranges">{{ implode(' · ', $entry['ranges']) }}</span>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </section>
            @endif

            <section id="contatti" class="restaurant-home__panel" aria-labelledby="contact-title">
                <h2 id="contact-title" class="restaurant-home__card-title">{{ __('restaurant::public_restaurants.contact.title.label') }}</h2>
                <ul class="contact-list">
                    @if ($profile['address'] !== null)
                        <li class="contact-list__row">
                            <span class="contact-list__icon" aria-hidden="true"><x-icon name="restaurant-map-pin" /></span>
                            <span>{{ $profile['address'] }}</span>
                        </li>
                    @endif
                    @if ($profile['phone'] !== null)
                        <li class="contact-list__row">
                            <span class="contact-list__icon" aria-hidden="true"><x-icon name="restaurant-phone" /></span>
                            <a href="tel:{{ preg_replace('/[^+0-9]/', '', $profile['phone']) }}">{{ $profile['phone'] }}</a>
                        </li>
                    @endif
                    @if ($profile['email'] !== null)
                        <li class="contact-list__row">
                            <span class="contact-list__icon" aria-hidden="true"><x-icon name="restaurant-envelope" /></span>
                            <a href="mailto:{{ $profile['email'] }}">{{ $profile['email'] }}</a>
                        </li>
                    @endif
                    @if ($profile['address'] === null && $profile['phone'] === null && $profile['email'] === null)
                        <li class="contact-list__row">
                            <span class="hint">{{ __('restaurant::public_restaurants.contact.empty.text') }}</span>
                        </li>
                    @endif
                </ul>
                @if ($profile['address'] !== null)
                    <a class="button button--ghost" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($profile['address']) }}" target="_blank" rel="noopener">{{ __('restaurant::public_restaurants.contact.directions.label') }}</a>
                @endif
            </section>
        </div>
    </div>
</section>
