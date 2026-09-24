<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a href="{{ url('/') }}" class="brand" aria-label="Trattoria, home">
            <span class="brand__mark" aria-hidden="true">✦</span>
            <span><strong>Trattoria</strong><small>cucina italiana</small></span>
        </a>
        <button type="button" class="icon-button nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="main-navigation">
            <span class="sr-only">Apri il menu</span><span aria-hidden="true">☰</span>
        </button>
        <nav id="main-navigation" class="site-nav" data-nav-menu aria-label="Navigazione principale">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/menu') }}">Menu</a>
            <a href="{{ url('/prenota') }}">Prenota un tavolo</a>
            <a href="{{ url('/asporto') }}">Asporto</a>
            <a href="{{ url('/servizio-al-tavolo') }}">Servizio al tavolo</a>
            <a class="button button--small button--primary" href="{{ url('/ordina') }}">Ordina ora</a>
        </nav>
    </div>
</header>
