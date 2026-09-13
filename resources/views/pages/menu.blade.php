@php
    $categories = collect();
    if (class_exists(\Modules\Restaurant\Models\ProductCategory::class)) {
        $categories = \Modules\Restaurant\Models\ProductCategory::query()->where('is_active', true)->with(['products' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')])->orderBy('sort_order')->get();
    }
@endphp
<x-layouts.app title="Trattoria · menu">
    <section class="section"><div class="container"><p class="eyebrow">Il nostro menu</p><h1>Piatti da condividere.</h1><p class="lead">Ingredienti riconoscibili, ricette italiane e proposte preparate al momento.</p>@if($categories->isNotEmpty())<div style="margin-top:2rem">@foreach($categories as $category)<section style="margin-bottom:2.5rem"><h2>{{ $category->name }}</h2><div class="menu-grid">@forelse($category->products as $product)<article class="menu-item"><div><h3>{{ $product->name }}</h3>@if($product->description)<p>{{ $product->description }}</p>@endif@if($product->allergens)<p class="hint">Allergeni: {{ $product->allergens }}</p>@endif</div><span class="price">€ {{ number_format((float) $product->price, 2, ',', '.') }}</span></article>@empty<p class="hint">Presto nuovi piatti in questa sezione.</p>@endforelse</div></section>@endforeach</div>@else<div class="card" style="margin-top:2rem"><h3>Menu in aggiornamento</h3><p>I piatti saranno disponibili a breve. Nel frattempo puoi prenotare il tuo tavolo.</p><a class="button" href="{{ url('/prenota') }}">Prenota</a></div>@endif</div></section>
</x-layouts.app>
