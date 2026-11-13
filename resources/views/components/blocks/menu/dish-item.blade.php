{{--
    Piatto del menu pubblico, card stile vetrina (foto grande in alto, nome, prezzo,
    badge dieta e chip allergeni con tooltip). Il <details> nativo apre il dettaglio:
    gallery, descrizione, ingredienti e badge completi — nessun JS richiesto.
    Variabile: $dish = ['product' => Product, 'allergens' => list<AllergenEnum>, 'available' => bool]
    `available` false = esaurito oggi: il piatto resta nel menu con l'etichetta "Esaurito".
    Immagini: `Product::imageUrls()` (foto caricate dal ristoratore o demo del modulo).
--}}
@php
    $product = $dish['product'];
    $images = $product->imageUrls();
    $euCases = array_values(array_filter($dish['allergens'], static fn ($case) => ! in_array($case, \Modules\Restaurant\Enums\AllergenEnum::dietaryCases(), true)));
    $dietCases = array_values(array_filter($dish['allergens'], static fn ($case) => in_array($case, \Modules\Restaurant\Enums\AllergenEnum::dietaryCases(), true)));
    $soldOut = ($dish['available'] ?? true) === false;
@endphp
<details @class(['dish', 'dish--soldout' => $soldOut])>
    <summary class="dish__summary">
        <span class="dish__media">
            @if ($images !== [])
                <img class="dish__photo" src="{{ $images[0] }}" alt="{{ $product->name }}" loading="lazy">
            @else
                <span class="dish__photo dish__photo--empty" aria-hidden="true"><x-icon name="restaurant-menu" /></span>
            @endif
            <span class="dish__price">{{ \Illuminate\Support\Number::currency((float) $product->price, 'EUR', app()->getLocale()) }}</span>
        </span>
        <span class="dish__main">
            <span class="dish__head">
                <span class="dish__name">{{ $product->name }}</span>
                @if ($soldOut)
                    <span class="dish__soldout">{{ __('restaurant::public_menu.dish.sold_out.label') }}</span>
                @endif
                <span class="dish__toggle" aria-hidden="true">+</span>
            </span>
            @if (filled($product->description))
                <span class="dish__teaser">{{ \Illuminate\Support\Str::limit($product->description, 90) }}</span>
            @endif
            <span class="dish__badges">
                @foreach ($dietCases as $case)
                    @include('pub_theme::components.blocks.menu.allergen-badge', ['case' => $case])
                @endforeach
                @foreach ($euCases as $case)
                    <span class="allergen-chip" style="--badge-color: {{ $case->color() }}" title="{{ $case->getLabel() }} — {{ $case->getDescription() }}">
                        <span class="allergen-chip__icon" aria-hidden="true"><x-icon name="restaurant-{{ $case->icon() }}" /></span>
                        <span class="sr-only">{{ $case->getLabel() }}</span>
                    </span>
                @endforeach
                @if ($euCases === [] && $dietCases === [])
                    <span class="hint">{{ __('restaurant::public_menu.dish.no_allergens.label') }}</span>
                @endif
            </span>
        </span>
    </summary>
    <div class="dish__body">
        @if ($images !== [])
            <ul class="dish__gallery" aria-label="{{ $product->name }}">
                @foreach ($images as $image)
                    <li><img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy"></li>
                @endforeach
            </ul>
        @endif
        @if (filled($product->description))<p>{{ $product->description }}</p>@endif
        @if ($product->ingredients->isNotEmpty())
            <h4 class="dish__subhead">{{ __('restaurant::public_menu.dish.ingredients.label') }}</h4>
            <ul class="dish__ingredients">
                @foreach ($product->ingredients as $ingredient)
                    <li>
                        <span>{{ $ingredient->name }}</span>
                        @foreach ($ingredient->allergenCases() as $case)
                            @include('pub_theme::components.blocks.menu.allergen-badge', ['case' => $case])
                        @endforeach
                    </li>
                @endforeach
            </ul>
        @endif
        @if ($euCases !== [])
            <h4 class="dish__subhead">{{ __('restaurant::public_menu.dish.allergens.label') }}</h4>
            <p class="dish__badges">
                @foreach ($euCases as $case)
                    @include('pub_theme::components.blocks.menu.allergen-badge', ['case' => $case])
                @endforeach
            </p>
        @endif
    </div>
</details>
