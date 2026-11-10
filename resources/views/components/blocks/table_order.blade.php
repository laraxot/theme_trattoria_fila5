{{--
    Pagina del tavolo raggiunta dal QR sul segnatavolo (`/{container0}/{slug0}` con slug0 = id tavolo).
    Dati: GetTableQrOrderAction (modulo Restaurant). Tavolo sconosciuto -> 404.
--}}
@php
    $tableId = (int) request()->route('slug0');
    $tableQrData = app(\Modules\Restaurant\Actions\Table\GetTableQrOrderAction::class)->execute($tableId);
    abort_if($tableQrData === null, 404);
@endphp
<section class="section">
    <div class="container">
        <p class="eyebrow">{{ __('pub_theme::table_order.order_lead') }}</p>
        <h1>{{ __('pub_theme::table_order.order_title', ['id' => $tableId]) }}</h1>

        {{-- Nessun dettaglio dell'ordine aperto: l'id tavolo e' un intero pubblico (vedi story table-order-menu). --}}
        <div class="card" style="margin:1rem 0"><p>{{ __('pub_theme::table_order.no_order') }}</p></div>

        <p style="margin-top:1.5rem"><a class="button button--ghost" href="{{ route('home') }}">{{ __('pub_theme::table_order.back_home') }}</a></p>
        <p class="hint">{{ __('pub_theme::table_order.footer_note') }}</p>
    </div>
</section>
