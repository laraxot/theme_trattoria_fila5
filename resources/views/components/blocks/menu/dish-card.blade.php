@php
    /** @var \Modules\Restaurant\Models\Product $product */
    /** @var list<\Modules\Restaurant\Enums\AllergenEnum> $allergens */
    $euCases = array_values(array_filter($allergens, static fn ($case) => ! in_array($case, \Modules\Restaurant\Enums\AllergenEnum::dietaryCases(), true)));
    $dietCases = array_values(array_filter($allergens, static fn ($case) => in_array($case, \Modules\Restaurant\Enums\AllergenEnum::dietaryCases(), true)));
    $ingredients = $product->relationLoaded('ingredients') ? $product->ingredients : collect();
    $images = $product->imageUrls();
    $cover = $images[0] ?? null;
@endphp
<details class="dish">
    <summary class="dish__summary">
        <span class="dish__media">
            @if ($cover)
                <img class="dish__photo" src="{{ $cover }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
            @else
                <span class="dish__photo dish__photo--empty" aria-hidden="true">@svg('restaurant-menu', 'dish__photo-icon', ['width' => 48, 'height' => 48])</span>
            @endif
            <span class="dish__price">{{ \Illuminate\Support\Number::currency((float) $product->price, 'EUR', app()->getLocale()) }}</span>
        </span>
        <span class="dish__main">
            <span class="dish__head">
                <span class="dish__name">{{ $product->name }}</span>
                <span class="dish__toggle" aria-hidden="true">+</span>
            </span>
            @if (filled($product->description))
                <span class="dish__teaser">{{ \Illuminate\Support\Str::limit((string) $product->description, 120) }}</span>
            @endif
            <span class="dish__badges">
                @foreach ($dietCases as $case)
                    @include('pub_theme::components.blocks.menu.allergen-badge', ['case' => $case])
                @endforeach
                @foreach (array_slice($euCases, 0, 5) as $case)
                    <span class="allergen-chip" style="--badge-color: {{ $case->color() }}" title="{{ $case->getLabel() }} — {{ $case->getDescription() }}">
                        <span class="allergen-chip__icon" aria-hidden="true">@svg('restaurant-'.$case->icon(), 'allergen-chip-svg', ['width' => 14, 'height' => 14])</span>
                        <span class="sr-only">{{ $case->getLabel() }}</span>
                    </span>
                @endforeach
                @if (count($euCases) > 5)
                    <span class="allergen-badge allergen-badge--more" style="--badge-color:#74655a" title="{{ __('restaurant::public_menu.dish.allergens.label') }}">
                        <span class="allergen-badge__label">+{{ count($euCases) - 5 }}</span>
                    </span>
                @endif
                @if ($euCases === [] && $dietCases === [])
                    <span class="hint">{{ __('restaurant::public_menu.dish.no_allergens.label') }}</span>
                @endif
            </span>
            <span class="dish__cta">{{ __('restaurant::public_menu.dish.details.label') }}</span>
        </span>
    </summary>
    <div class="dish__body">
        @if ($images !== [])
            <ul class="dish__gallery" aria-label="{{ __('restaurant::public_menu.dish.photos.label') }}">
                @foreach (array_slice($images, 0, 3) as $image)
                    <li><img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy" decoding="async"></li>
                @endforeach
            </ul>
        @endif
        @if (filled($product->description))<p class="dish__description">{{ $product->description }}</p>@endif
        @if ($ingredients->isNotEmpty())
            <h4 class="dish__subhead">{{ __('restaurant::public_menu.dish.ingredients.label') }}</h4>
            <ul class="dish__ingredients">
                @foreach ($ingredients as $ingredient)
                    <li>
                        <span>{{ $ingredient->name }}</span>
                        @foreach ($ingredient->allergenCases() as $case)
                            @include('pub_theme::components.blocks.menu.allergen-badge', ['case' => $case])
                        @endforeach
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="dish__facts">
            @if ($dietCases !== [])
                <h4 class="dish__subhead">{{ __('restaurant::public_menu.dish.diets.label') }}</h4>
                <p class="dish__badges">
                    @foreach ($dietCases as $case)
                        @include('pub_theme::components.blocks.menu.allergen-badge', ['case' => $case])
                    @endforeach
                </p>
            @endif
            <h4 class="dish__subhead">{{ __('restaurant::public_menu.dish.allergens.label') }}</h4>
            @if ($euCases !== [])
                <p class="dish__badges">
                    @foreach ($euCases as $case)
                        @include('pub_theme::components.blocks.menu.allergen-badge', ['case' => $case])
                    @endforeach
                </p>
            @else
                <p class="hint">{{ __('restaurant::public_menu.dish.no_allergens.label') }}</p>
            @endif
        </div>
    </div>
</details>
