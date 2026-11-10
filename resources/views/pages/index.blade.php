<x-layouts.app :title="__('pub_theme::home.title')" :description="__('pub_theme::home.description')">
    <section class="hero">
        <div class="container hero__grid">
            <div class="hero__copy">
                <h1>{{ __('pub_theme::home.title') }}</h1>
                <p class="hero__lead">{{ __('pub_theme::home.lead') }}</p>
                <div class="actions">
                    <a class="button button--ghost" href="{{ route('container0.index', ['container0' => 'menu']) }}">{{ __('pub_theme::home.discover_menu') }}</a>
                    <a class="button button--secondary" href="{{ route('container0.index', ['container0' => 'prenota']) }}">{{ __('pub_theme::home.book_table') }}</a>
                </div>
            </div>
            <aside class="hero__aside" aria-label="{{ __('pub_theme::home.today_in_room') }}">
                <p class="hero__aside-label">{{ __('pub_theme::home.today_in_room') }}</p>
                <p class="hero__aside-time">{{ __('pub_theme::home.hours') }}<span>—</span>23:00</p>
                <p class="hero__aside-note">{{ __('pub_theme::home.kitchen_open_until') }}<br>{{ config('app.restaurant_address', 'Via della Tavola, 12') }}</p>
            </aside>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <h2>{{ __('pub_theme::home.how_can_we_serve_you') }}</h2>
            <p class="lead">{{ __('pub_theme::home.book_or_order') }}</p>
            <div class="cards" style="margin-top:1.5rem">
                <a class="card" href="{{ route('container0.index', ['container0' => 'prenota']) }}">
                    <h3>{{ __('pub_theme::home.card_table.title') }}</h3>
                    <p>{{ __('pub_theme::home.card_table.description') }}</p>
                    <span class="card__action">{{ __('pub_theme::home.card_table.action') }}</span>
                </a>
                <a class="card" href="{{ route('container0.index', ['container0' => 'ordine', 'mode' => 'takeaway']) }}">
                    <h3>{{ __('pub_theme::home.card_takeaway.title') }}</h3>
                    <p>{{ __('pub_theme::home.card_takeaway.description') }}</p>
                    <span class="card__action">{{ __('pub_theme::home.card_takeaway.action') }}</span>
                </a>
                <a class="card" href="{{ route('container0.index', ['container0' => 'ordine', 'mode' => 'dine_in']) }}">
                    <h3>{{ __('pub_theme::home.card_dine_in.title') }}</h3>
                    <p>{{ __('pub_theme::home.card_dine_in.description') }}</p>
                    <span class="card__action">{{ __('pub_theme::home.card_dine_in.action') }}</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>