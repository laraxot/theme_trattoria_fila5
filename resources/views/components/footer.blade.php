<footer class="site-footer">
    <div class="container site-footer__grid">
        <div><a class="brand brand--footer" href="{{ url('/') }}"><span class="brand__mark" aria-hidden="true">✦</span><span><strong>Trattoria</strong><small>cucina italiana</small></span></a><p class="site-footer__note">Sapori autentici, servizio con il sorriso.</p></div>
        <div><h2 class="footer-heading">Vieni a trovarci</h2><p>Via della Tavola, 12<br>{{ config('app.restaurant_city', 'Roma') }}</p></div>
        <div><h2 class="footer-heading">Orari</h2><p>Lun–Dom · 12:00–23:00<br><a href="{{ url('/prenota') }}">Prenota il tuo tavolo →</a></p></div>
    </div>
    <div class="container site-footer__bottom"><span>© {{ date('Y') }} {{ config('app.name', 'Trattoria') }}</span><span>Fatto con cura in Italia</span></div>
</footer>
