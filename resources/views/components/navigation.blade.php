<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ __('pub_theme::nav.home') }}">
            <span class="brand__mark" aria-hidden="true">✦</span>
            <span><strong>{{ __('pub_theme::nav.brand') }}</strong><small>{{ __('pub_theme::nav.subtitle') }}</small></span>
        </a>
        <button type="button" class="icon-button nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="main-navigation">
            <span class="sr-only">{{ __('pub_theme::nav.open_menu') }}</span><span data-nav-glyph aria-hidden="true">☰</span>
        </button>
        <nav id="main-navigation" class="site-nav" data-nav-menu aria-label="{{ __('pub_theme::nav.main_menu') }}">
            <a href="{{ route('home') }}">{{ __('pub_theme::nav.home') }}</a>
            <a href="{{ route('container0.index', ['container0' => 'menu']) }}">{{ __('pub_theme::nav.menu') }}</a>
            <a href="{{ route('container0.index', ['container0' => 'prenota']) }}">{{ __('pub_theme::nav.book_table') }}</a>
            <a href="{{ route('container0.index', ['container0' => 'ordine', 'mode' => 'takeaway']) }}">{{ __('pub_theme::nav.takeaway') }}</a>
            <a href="{{ route('container0.index', ['container0' => 'ordine', 'mode' => 'dine_in']) }}">{{ __('pub_theme::nav.table_service') }}</a>
            <a class="button button--small button--primary" href="{{ route('container0.index', ['container0' => 'ordine']) }}">{{ __('pub_theme::nav.order_now') }}</a>
        </nav>
    </div>
</header>