{{--
    Menu pubblico del ristorante, raggiunto da rotta container (`/{container0}/{slug0}`).
    `?senza[]=gluten&senza[]=milk` esclude allergeni; `?dieta[]=vegan&dieta[]=kosher`
    applica filtri inclusivi. Dati da Actions del modulo Restaurant; vista solo presentazione.
--}}
@php
    $restaurantSlug = (string) request()->route('slug0', '');
    $restaurantModel = app(\Modules\Restaurant\Actions\Menu\FindPublicRestaurantAction::class)->execute($restaurantSlug);
    $restaurantName = (string) $restaurantModel->getAttribute('name');
    $excluded = app(\Modules\Restaurant\Actions\Menu\ParseExcludedAllergensAction::class)->execute(request()->query('senza', []));
    $diets = app(\Modules\Restaurant\Actions\Menu\ParseDietaryFiltersAction::class)->execute(request()->query('dieta', []));
    $menu = app(\Modules\Restaurant\Actions\Menu\GetPublicMenuAction::class)->execute($restaurantModel, $excluded, $diets);
    $allergens = \Modules\Restaurant\Enums\AllergenEnum::euAllergenCases();
    $dietary = \Modules\Restaurant\Enums\AllergenEnum::dietaryCases();
    $excludedValues = array_map(static fn ($case) => $case->value, $excluded);
    $dietValues = array_map(static fn ($case) => $case->value, $diets);
    $dishCount = array_sum(array_map(static fn ($section) => count($section['dishes']), $menu));
    $activeFilters = array_merge($excluded, $diets);
    $filterAction = request()->url();
@endphp
<section class="section menu-page">
    <div class="container">
        <header class="menu-hero">
            <p class="eyebrow">{{ $restaurantName }}</p>
            <h1>{{ $title ?? __('restaurant::public_menu.title.text', ['name' => $restaurantName]) }}</h1>
            <p class="lead">{{ $lead ?? __('restaurant::public_menu.lead.text') }}</p>
        </header>

        <details class="card menu-filter" @if ($activeFilters !== []) open @endif>
            <summary class="menu-filter__summary">{{ __('restaurant::public_menu.filter.title.label') }}</summary>
            <form method="get" action="{{ $filterAction }}" class="menu-filter__form">
                <p class="hint">{{ __('restaurant::public_menu.filter.help.text') }}</p>
                <div class="filter-grid">
                    @foreach ($allergens as $allergen)
                        <label class="filter-choice" title="{{ $allergen->getLabel() }} — {{ $allergen->getDescription() }}">
                            <input type="checkbox" name="senza[]" value="{{ $allergen->value }}" @checked(in_array($allergen->value, $excludedValues, true))>
                            <span class="filter-choice__icon" aria-hidden="true">@svg('restaurant-'.$allergen->icon(), 'allergen-icon', ['width' => 18, 'height' => 18])</span>
                            <span class="filter-choice__label">{{ $allergen->getLabel() }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="filter-group">
                    <p class="filter-group__title"><strong>{{ __('restaurant::public_menu.filter.diet.title.label') }}</strong></p>
                    <p class="hint">{{ __('restaurant::public_menu.filter.diet.help.text') }}</p>
                    <div class="filter-grid">
                        @foreach ($dietary as $diet)
                            <label class="filter-choice filter-choice--diet" title="{{ $diet->getLabel() }} — {{ $diet->getDescription() }}">
                                <input type="checkbox" name="dieta[]" value="{{ $diet->value }}" @checked(in_array($diet->value, $dietValues, true))>
                                <span class="filter-choice__icon" aria-hidden="true">@svg('restaurant-'.$diet->icon(), 'allergen-icon', ['width' => 18, 'height' => 18])</span>
                                <span class="filter-choice__label">{{ $diet->getLabel() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="actions">
                    <button type="submit" class="button">{{ __('restaurant::public_menu.filter.apply.label') }}</button>
                    @if ($activeFilters !== [])
                        <a class="button button--ghost" href="{{ $filterAction }}">{{ __('restaurant::public_menu.filter.reset.label') }}</a>
                    @endif
                </div>
            </form>
        </details>

        @include('pub_theme::components.blocks.menu.allergen-legend')

        @if ($activeFilters !== [])
            <div class="menu-status" role="status">
                <p>
                    {{ trans_choice('restaurant::public_menu.filter.active.text', $dishCount, ['count' => $dishCount]) }}
                    <strong>{{ implode(', ', array_map(static fn ($case) => $case->getLabel(), $activeFilters)) }}</strong>
                </p>
            </div>
        @endif

        @forelse ($menu as $section)
            <section class="menu-section">
                <div class="menu-section__head">
                    <h2>{{ $section['category']->name }}</h2>
                    <span class="menu-section__count">{{ count($section['dishes']) }}</span>
                </div>
                <div class="dish-grid">
                    @foreach ($section['dishes'] as $dish)
                        @include('pub_theme::components.blocks.menu.dish-item', ['dish' => $dish])
                    @endforeach
                </div>
            </section>
        @empty
            <div class="card menu-empty">
                <p>{{ $activeFilters !== [] ? __('restaurant::public_menu.empty_filtered_message.text') : __('restaurant::public_menu.empty_message.text') }}</p>
            </div>
        @endforelse

        <p class="hint menu-disclaimer">{{ __('restaurant::public_menu.disclaimer_text.text') }}</p>
    </div>
</section>
