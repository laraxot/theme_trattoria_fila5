{{--
    Home del portale: piu' ristoranti, ognuno con il suo minisito (menu con filtro allergeni,
    prenotazione, ordine dal QR del tavolo). Niente orari o indirizzi qui: sono di ogni ristorante
    e stanno nella sua scheda. Le funzioni elencate sono solo quelle disponibili.
--}}
<x-layouts.app :title="__('pub_theme::home.title')" :description="__('pub_theme::home.description')">
    <section class="hero">
        <div class="container hero__grid">
            <div class="hero__copy">
                <p class="eyebrow">{{ __('pub_theme::home.eyebrow') }}</p>
                <h1>{{ __('pub_theme::home.title') }}</h1>
                <p class="hero__lead">{{ __('pub_theme::home.lead') }}</p>
                <div class="actions">
                    <a class="button button--primary" href="{{ route('container0.index', ['container0' => 'restaurants']) }}">{{ __('pub_theme::home.discover_restaurants') }}</a>
                    <a class="button button--ghost" href="{{ route('container0.index', ['container0' => 'prenota']) }}">{{ __('pub_theme::home.book_table') }}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2>{{ __('pub_theme::home.how_it_works') }}</h2>
            <p class="lead">{{ __('pub_theme::home.how_it_works_lead') }}</p>
            <div class="cards" style="margin-top:1.5rem">
                @foreach (['menu' => ['container0.index', ['container0' => 'menu']], 'book' => ['container0.index', ['container0' => 'prenota']], 'table' => ['container0.index', ['container0' => 'ordine']]] as $key => [$routeName, $params])
                    <a class="card" href="{{ route($routeName, $params) }}">
                        <h3>{{ __('pub_theme::home.steps.'.$key.'.title') }}</h3>
                        <p>{{ __('pub_theme::home.steps.'.$key.'.description') }}</p>
                        <span class="card__action">{{ __('pub_theme::home.steps.'.$key.'.action') }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @include('pub_theme::components.blocks.restaurant_list', [
        'heading' => 'h2',
        'title' => __('pub_theme::home.restaurants_title'),
        'lead' => __('pub_theme::home.restaurants_lead'),
    ])

    <section class="section">
        <div class="container">
            <div class="card restaurateur-cta">
                <div>
                    <h2>{{ __('pub_theme::home.restaurateur.title') }}</h2>
                    <p>{{ __('pub_theme::home.restaurateur.description') }}</p>
                </div>
                <div class="actions">
                    <a class="button button--primary" href="{{ route('container0.index', ['container0' => 'contatti']) }}">{{ __('pub_theme::home.restaurateur.contact') }}</a>
                    <a class="button button--ghost" href="{{ route('login') }}">{{ __('pub_theme::home.restaurateur.login') }}</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
