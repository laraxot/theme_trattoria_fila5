@php
    $allergenCases = \Modules\Restaurant\Enums\AllergenEnum::euAllergenCases();
    $dietaryCases = \Modules\Restaurant\Enums\AllergenEnum::dietaryCases();
@endphp
<section class="allergen-legend" aria-labelledby="allergen-legend-title">
    <h2 id="allergen-legend-title" class="allergen-legend__title">
        {{ __('restaurant::public_menu.legend.title.label') }}
    </h2>
    <ul class="allergen-legend__list">
        @foreach (array_merge($allergenCases, $dietaryCases) as $case)
            <li class="allergen-legend__item" title="{{ $case->getLabel() }}">
                <span class="allergen-badge" style="--badge-color: {{ $case->color() }}">
                    <span class="allergen-badge__icon" aria-hidden="true">@svg('restaurant-'.$case->icon(), 'allergen-icon', ['width' => 14, 'height' => 14])</span>
                    <abbr class="allergen-badge__code" title="{{ $case->getLabel() }}">{{ $case->code() }}</abbr>
                </span>
                <span class="allergen-legend__name">
                    <span>{{ $case->getDescription() }}</span>
                </span>
            </li>
        @endforeach
    </ul>
</section>
