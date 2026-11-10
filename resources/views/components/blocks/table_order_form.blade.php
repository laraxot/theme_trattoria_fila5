<?php

declare(strict_types=1);

use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Modules\Restaurant\Actions\Menu\FindPublicRestaurantAction;
use Modules\Restaurant\Actions\Menu\GetPublicMenuAction;
use Modules\Restaurant\Actions\Order\CreateCustomerOrderAction;
use Modules\Restaurant\Actions\Order\SendOrderToStationsAction;
use Modules\Restaurant\Models\DiningTable;
use Modules\Restaurant\Models\ProductModifier;

/*
 * Ordinazione dal tavolo nel minisito ristorante
 * (`/{lang}/restaurants/{slug}/table/{num}/order`, pagina CMS `restaurants.table_order`).
 *
 * - Ristorante: `slug0` → FindPublicRestaurantAction (404 se sconosciuto).
 * - Tavolo: `slug1` → DiningTable attivo per id o nome (404 altrimenti).
 * - Menu: solo prodotti del tenant via GetPublicMenuAction; i modificatori
 *   arrivano da `product_modifiers` attivi del stesso prodotto.
 * - Carrello: qty + note libere + modificatori selezionati. `submit()` invia
 *   nomi di modificatori (mai price_delta dal client) e CreateCustomerOrderAction
 *   li rivalida sul catalogo. Poi SendOrderToStationsAction smista le righe su
 *   kitchen / bar / external_bar (print_station della categoria).
 * - `idempotencyKey` generata a mount: refresh/doppio submit non duplicano.
 */
new class extends Component {
    public string $restaurantName = '';

    public string $tableName = '';

    /** @var array<int, array{id: int, name: string, price: float, description: string, images: list<string>, modifiers: list<array{id: int, name: string, price_delta: float}>}> */
    public array $menu = [];

    /** @var array<int, array{qty: int, notes: string, mods: array<int, bool>}> product_id => riga carrello */
    public array $cart = [];

    /** @var array<int, array{name: string, qty: int, total: float, detail: string}> */
    public array $cartLines = [];

    public int $covers = 2;

    public bool $sent = false;

    public bool $sentToStations = false;

    public string $sendWarning = '';

    public float $orderTotal = 0.0;

    public int $tableId = 0;

    public string $idempotencyKey = '';

    public function mount(): void
    {
        $restaurant = app(FindPublicRestaurantAction::class)->execute((string) request()->route('slug0', ''));
        $table = $this->resolveTable((string) request()->route('slug1', ''));
        abort_if($table === null, 404);

        $this->restaurantName = (string) $restaurant->getAttribute('name');
        $this->tableName = (string) $table->name;
        $this->tableId = (int) $table->id;
        $this->covers = max(1, min(12, (int) ($table->seats ?? 2)));
        $this->idempotencyKey = 'qr-'.$this->tableId.'-'.Str::uuid()->toString();

        $products = [];
        foreach (app(GetPublicMenuAction::class)->execute($restaurant) as $section) {
            foreach ($section['dishes'] as $dish) {
                $products[(int) $dish['product']->id] = $dish['product'];
            }
        }

        $modifiers = ProductModifier::query()
            ->whereIn('product_id', array_keys($products))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('product_id');

        foreach ($products as $product) {
            $this->menu[] = [
                'id' => (int) $product->id,
                'name' => (string) $product->name,
                'price' => (float) $product->price,
                'description' => (string) ($product->description ?? ''),
                'images' => array_values(array_map('strval', (array) ($product->images ?? []))),
                'modifiers' => $modifiers->get($product->id, collect())->map(static fn (ProductModifier $m): array => [
                    'id' => (int) $m->id,
                    'name' => (string) $m->name,
                    'price_delta' => (float) $m->price_delta,
                ])->values()->all(),
            ];
        }
    }

    private function resolveTable(string $ref): ?DiningTable
    {
        $query = DiningTable::query()->where('is_active', true);

        if (ctype_digit($ref)) {
            $table = (clone $query)->find((int) $ref);
            if ($table !== null) {
                return $table;
            }
        }

        return $query->where('name', $ref)->first();
    }

    public function inc(int $productId): void
    {
        $this->adjust($productId, 1);
    }

    public function dec(int $productId): void
    {
        $this->adjust($productId, -1);
    }

    private function adjust(int $productId, int $delta): void
    {
        if (! $this->isAllowed($productId)) {
            return;
        }

        $row = $this->cart[$productId] ?? ['qty' => 0, 'notes' => '', 'mods' => []];
        $row['qty'] = max(0, min(20, $row['qty'] + $delta));

        if ($row['qty'] === 0) {
            unset($this->cart[$productId]);
        } else {
            $this->cart[$productId] = $row;
        }

        $this->syncCartLines();
    }

    public function submit(): void
    {
        $this->validate([
            'covers' => ['required', 'integer', 'min:1', 'max:12'],
            'cart' => ['required', 'array', 'min:1'],
        ]);

        $items = [];
        foreach ($this->cart as $productId => $row) {
            $productId = (int) $productId;
            $qty = (int) ($row['qty'] ?? 0);
            if ($qty < 1 || ! $this->isAllowed($productId)) {
                continue;
            }

            $modifiers = [];
            foreach ($this->menu as $entry) {
                if ($entry['id'] !== $productId) {
                    continue;
                }
                foreach ($entry['modifiers'] as $modifier) {
                    if (! empty($row['mods'][$modifier['id']])) {
                        $modifiers[] = ['name' => $modifier['name']];
                    }
                }
            }

            $items[] = [
                'product_id' => $productId,
                'quantity' => min(20, $qty),
                'notes' => trim((string) ($row['notes'] ?? '')) ?: null,
                'modifiers' => $modifiers,
            ];
        }

        if ($items === []) {
            $this->addError('order', __('pub_theme::table_order.cart_empty'));

            return;
        }

        $order = app(CreateCustomerOrderAction::class)->execute(
            $this->tableId,
            $this->idempotencyKey,
            $items,
            (int) $this->covers,
        );

        try {
            app(SendOrderToStationsAction::class)->execute($order->id);
            $this->sentToStations = true;
        } catch (\Throwable $e) {
            $this->sendWarning = $e->getMessage();
        }

        $this->orderTotal = (float) $order->total;
        $this->sent = true;
        $this->cart = [];
        $this->cartLines = [];
    }

    private function isAllowed(int $productId): bool
    {
        foreach ($this->menu as $entry) {
            if ($entry['id'] === $productId) {
                return true;
            }
        }

        return false;
    }

    private function syncCartLines(): void
    {
        $catalog = [];
        foreach ($this->menu as $entry) {
            $mods = [];
            foreach ($entry['modifiers'] as $modifier) {
                $mods[$modifier['id']] = $modifier;
            }
            $catalog[$entry['id']] = ['name' => $entry['name'], 'price' => $entry['price'], 'modifiers' => $mods];
        }

        $lines = [];
        foreach ($this->cart as $productId => $row) {
            $info = $catalog[(int) $productId] ?? null;
            $qty = (int) ($row['qty'] ?? 0);
            if ($info === null || $qty < 1) {
                continue;
            }

            $unit = $info['price'];
            $detail = [];
            foreach (($row['mods'] ?? []) as $modId => $on) {
                $modifier = $info['modifiers'][(int) $modId] ?? null;
                if ($on && $modifier !== null) {
                    $unit += $modifier['price_delta'];
                    $detail[] = $modifier['name'];
                }
            }
            $notes = trim((string) ($row['notes'] ?? ''));
            if ($notes !== '') {
                $detail[] = $notes;
            }

            $lines[] = [
                'name' => $info['name'],
                'qty' => $qty,
                'total' => $unit * $qty,
                'detail' => implode(' · ', $detail),
            ];
        }

        $this->cartLines = $lines;
    }
};
?>

<section class="section table-order-page">
    <div class="container">
        @volt('table-order-form')
        <div>
            <header class="menu-hero">
                <p class="eyebrow">{{ $restaurantName }}</p>
                <h1>{{ __('pub_theme::table_order.form_title', ['table' => $tableName]) }}</h1>
                <p class="lead">{{ __('pub_theme::table_order.form_lead') }}</p>
            </header>

            @if ($sent)
                <div class="card" role="status">
                    <p><strong>{{ __('pub_theme::table_order.sent_title') }}</strong></p>
                    <p class="hint">{{ __('pub_theme::table_order.sent_text') }}</p>
                    @if ($sentToStations)
                        <p class="hint">{{ __('pub_theme::table_order.sent_stations') }}</p>
                    @elseif ($sendWarning !== '')
                        <p class="hint" role="alert">{{ __('pub_theme::table_order.send_failed', ['error' => $sendWarning]) }}</p>
                    @endif
                    @if ($orderTotal > 0)
                        <p>{{ __('pub_theme::table_order.sent_total', ['total' => Number::currency($orderTotal, 'EUR', app()->getLocale())]) }}</p>
                    @endif
                </div>
            @else
                <div class="table-order__layout">
                    <div class="dish-grid table-order__menu">
                        @foreach ($menu as $dish)
                            <article class="dish dish--order">
                                <div class="dish__media">
                                    @if ($dish['images'] !== [])
                                        <img class="dish__photo" src="{{ asset('assets/Restaurant/'.e($dish['images'][0])) }}" alt="{{ $dish['name'] }}" loading="lazy">
                                    @else
                                        <span class="dish__photo dish__photo--empty" aria-hidden="true"><x-icon name="restaurant-menu" /></span>
                                    @endif
                                    <span class="dish__price">{{ Number::currency($dish['price'], 'EUR', app()->getLocale()) }}</span>
                                </div>
                                <div class="dish__main">
                                    <span class="dish__head"><span class="dish__name">{{ $dish['name'] }}</span></span>
                                    @if ($dish['description'] !== '')<span class="dish__teaser">{{ Str::limit($dish['description'], 80) }}</span>@endif
                                    <div class="qty-stepper" role="group" aria-label="{{ $dish['name'] }}">
                                        <button type="button" class="stepper-btn" wire:click="dec({{ $dish['id'] }})" @disabled(($cart[$dish['id']]['qty'] ?? 0) < 1) aria-label="{{ __('pub_theme::table_order.decrease') }}">&minus;</button>
                                        <output class="stepper-value" aria-live="polite">{{ $cart[$dish['id']]['qty'] ?? 0 }}</output>
                                        <button type="button" class="stepper-btn" wire:click="inc({{ $dish['id'] }})" aria-label="{{ __('pub_theme::table_order.increase') }}">+</button>
                                    </div>
                                    @if (($cart[$dish['id']]['qty'] ?? 0) > 0)
                                        <details class="dish__customize">
                                            <summary>{{ __('pub_theme::table_order.customize') }}</summary>
                                            <div class="dish__customize-body">
                                                @foreach ($dish['modifiers'] as $modifier)
                                                    <label class="check">
                                                        <input type="checkbox" wire:model="cart.{{ $dish['id'] }}.mods.{{ $modifier['id'] }}">
                                                        <span>{{ $modifier['name'] }}@if ($modifier['price_delta'] > 0) <em>+{{ Number::currency($modifier['price_delta'], 'EUR', app()->getLocale()) }}</em>@endif</span>
                                                    </label>
                                                @endforeach
                                                <label class="check check--block">
                                                    <span class="check__label">{{ __('pub_theme::table_order.notes_label') }}</span>
                                                    <input type="text" wire:model="cart.{{ $dish['id'] }}.notes" maxlength="255" placeholder="{{ __('pub_theme::table_order.notes_placeholder') }}">
                                                </label>
                                            </div>
                                        </details>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="card table-order__cart" aria-live="polite">
                        <h2 class="restaurant-home__card-title">{{ __('pub_theme::table_order.cart_title') }}</h2>
                        @if ($cartLines === [])
                            <p class="hint">{{ __('pub_theme::table_order.cart_empty') }}</p>
                        @else
                            <ul class="table-order__lines">
                                @foreach ($cartLines as $line)
                                    <li>
                                        <span>{{ $line['name'] }} &times; {{ $line['qty'] }}@if ($line['detail'] !== '')<small class="table-order__detail">{{ $line['detail'] }}</small>@endif</span>
                                        <span>{{ Number::currency($line['total'], 'EUR', app()->getLocale()) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <p class="table-order__total"><strong>{{ __('pub_theme::table_order.total_label') }}</strong> {{ Number::currency(array_sum(array_column($cartLines, 'total')), 'EUR', app()->getLocale()) }}</p>
                            <div class="field">
                                <label for="order_covers">{{ __('pub_theme::table_order.covers_label') }}</label>
                                <input id="order_covers" type="number" wire:model="covers" min="1" max="12">
                                @error('covers')<p class="hint">{{ $message }}</p>@enderror
                            </div>
                            <button type="button" class="button button--primary" wire:click="submit" wire:loading.attr="disabled" @disabled($cartLines === [])>{{ __('pub_theme::table_order.submit') }}</button>
                            @error('order')<p class="hint" role="alert">{{ $message }}</p>@enderror
                        @endif
                    </aside>
                </div>
            @endif
        </div>
        @endvolt
    </div>
</section>
