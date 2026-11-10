{{-- Blocco CMS del menu (pagina `menu.index`). Dati: GetActiveMenuCategoriesAction (modulo Restaurant). --}}
@php
    $categories = app(\Modules\Restaurant\Actions\Menu\GetActiveMenuCategoriesAction::class)->execute();
@endphp
<section class="section">
    <div class="container">
        @if (filled($eyebrow ?? null))<p class="eyebrow">{{ $eyebrow }}</p>@endif
        @if (filled($title ?? null))<h1>{{ $title }}</h1>@endif
        @if (filled($lead ?? null))<p class="lead">{{ $lead }}</p>@endif

        @if ($categories->isNotEmpty())
            <div style="margin-top:2rem">
                @foreach ($categories as $category)
                    <section style="margin-bottom:2.5rem">
                        <h2>{{ $category->name }}</h2>
                        <div class="menu-grid">
                            @forelse ($category->products as $product)
                                <article class="menu-item">
                                    <div>
                                        <h3>{{ $product->name }}</h3>
                                        @if ($product->description)<p>{{ $product->description }}</p>@endif
                                        @if ($product->allergens)<p class="hint">{{ __('restaurant::menu_block.product.allergens.text', ['list' => $product->allergens]) }}</p>@endif
                                    </div>
                                    <span class="price">€ {{ number_format((float) $product->price, 2, ',', '.') }}</span>
                                </article>
                            @empty
                                <p class="hint">{{ $emptyCategoryText ?? '' }}</p>
                            @endforelse
                        </div>
                    </section>
                @endforeach
            </div>
        @else
            <div class="card" style="margin-top:2rem">
                @if (filled($emptyTitle ?? null))<h3>{{ $emptyTitle }}</h3>@endif
                @if (filled($emptyText ?? null))<p>{{ $emptyText }}</p>@endif
                @if (filled($ctaLabel ?? null) && filled($ctaUrl ?? null))<a class="button" href="{{ $ctaUrl }}">{{ $ctaLabel }}</a>@endif
            </div>
        @endif
    </div>
</section>
