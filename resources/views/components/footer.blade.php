<footer class="site-footer">
    <div class="container site-footer__grid">
        <div><a class="brand brand--footer" href="{{ route('home') }}"><span class="brand__mark" aria-hidden="true">✦</span><span><strong>{{ __('pub_theme::nav.brand') }}</strong><small>{{ __('pub_theme::nav.subtitle') }}</small></span></a><p class="site-footer__note">{{ __('pub_theme::footer.tagline') }}</p></div>
        <div><h2 class="footer-heading">{{ __('pub_theme::footer.visit_us') }}</h2><p>{{ config('app.restaurant_address', 'Via della Tavola, 12') }}<br>{{ config('app.restaurant_city', 'Roma') }}</p></div>
        <div><h2 class="footer-heading">{{ __('pub_theme::footer.hours') }}</h2><p>{{ __('pub_theme::footer.hours_value') }}<br><a href="{{ route('container0.index', ['container0' => 'prenota']) }}">{{ __('pub_theme::footer.book_table') }} →</a></p></div>
    </div>
    <div class="container site-footer__bottom"><span>© {{ date('Y') }} {{ config('app.name', 'Trattoria') }}</span><span>{{ __('pub_theme::footer.made_in_italy') }}</span></div>
</footer>