{{--
    Vecchio link del segnatavolo `/{lang}/table/{id}/qr` (pagina CMS `table.view`).
    L'ordine al tavolo ora passa SOLO dal link firmato `/restaurants/{ristorante}/table/{id}/order`
    (IssueTableOrderUrlAction). Questa pagina resta per i QR gia' stampati, ma non legge il database:
    stessa risposta per qualsiasi id, cosi' non rivela quali tavoli esistono.
--}}
<section class="section">
    <div class="container">
        <p class="eyebrow">{{ __('pub_theme::table_order.order_lead') }}</p>
        <h1>{{ __('pub_theme::table_order.legacy_title') }}</h1>

        <div class="card" style="margin:1rem 0"><p>{{ __('pub_theme::table_order.legacy_text') }}</p></div>

        <p style="margin-top:1.5rem"><a class="button button--ghost" href="{{ route('home') }}">{{ __('pub_theme::table_order.back_home') }}</a></p>
    </div>
</section>
