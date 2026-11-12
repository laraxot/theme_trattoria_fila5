<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Modules\Restaurant\Actions\Menu\FindPublicRestaurantAction;
use Modules\Restaurant\Actions\Menu\ListPublicRestaurantsAction;
use Modules\Restaurant\Actions\Menu\ResolveRouteRestaurantAction;
use Modules\Restaurant\Actions\Reservations\CreatePublicReservationAction;
use Modules\Xot\Datas\XotData;

/*
 * Form pubblico di prenotazione (blocco CMS `reservation_form`).
 * Dati facoltativi dal JSON: title, lead, heading ('h1' quando il modulo e' il contenuto principale della pagina, default 'h2'), submit_label, success_message.
 * Le etichette dei campi vengono da `pub_theme::reservation.*`.
 *
 * - Ristorante: su `/restaurants/{slug}/book` la prenotazione e' di quel ristorante. Si risolve
 *   al primo caricamento dalla rotta e si tiene in `restaurantId` (#[Locked]: il browser non puo'
 *   cambiarlo). Su `/prenota` (portale) il cliente sceglie il ristorante dall'elenco di quelli attivi
 *   (`restaurantSlug`, anche precompilato da `?ristorante=slug`): senza ristorante la prenotazione
 *   non arriverebbe a nessun ristoratore.
 * - Telefono obbligatorio: e' l'unico modo per il ristorante di confermare o disdire.
 * - Data e ora non nel passato (anche oggi: ora successiva a adesso).
 * - Al massimo 5 invii ogni 10 minuti per indirizzo IP, contro lo spam.
 */
new class extends Component {
    public string $customer_name = '';

    public string $phone = '';

    public string $email = '';

    public int $party_size = 2;

    public string $reservation_date = '';

    public string $reservation_time = '';

    public string $notes = '';

    public bool $sent = false;

    #[Locked]
    public ?string $restaurantId = null;

    /** Scelta del cliente su `/prenota`, quando la rotta non indica il ristorante. */
    public string $restaurantSlug = '';

    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');

        $restaurant = app(ResolveRouteRestaurantAction::class)->execute(
            (string) request()->route('container0', ''),
            (string) request()->route('slug0', ''),
        );
        $this->restaurantId = $restaurant === null ? null : (string) $restaurant->getKey();

        $preselected = request()->query('ristorante');
        if ($this->restaurantId === null && \is_string($preselected) && \in_array($preselected, $this->restaurantSlugs(), true)) {
            $this->restaurantSlug = $preselected;
        }
    }

    /**
     * @return list<string>
     */
    private function restaurantSlugs(): array
    {
        return app(ListPublicRestaurantsAction::class)->execute()
            ->map(static fn ($restaurant): string => (string) $restaurant->getAttribute('slug'))
            ->values()
            ->all();
    }

    /**
     * @return array<string, string> slug => nome, per la scelta su `/prenota`
     */
    public function restaurantOptions(): array
    {
        return app(ListPublicRestaurantsAction::class)->execute()
            ->mapWithKeys(static fn ($restaurant): array => [(string) $restaurant->getAttribute('slug') => (string) $restaurant->getAttribute('name')])
            ->all();
    }

    public function decrementParty(): void
    {
        $this->party_size = max(1, $this->party_size - 1);
    }

    public function incrementParty(): void
    {
        $this->party_size = min(12, $this->party_size + 1);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'restaurantSlug' => $this->restaurantId === null
                ? ['required', 'string', Rule::in($this->restaurantSlugs())]
                : ['nullable'],
            'customer_name' => ['required', 'string', 'max:191'],
            'phone' => ['required', 'string', 'min:6', 'max:50', 'regex:/^[0-9+().\s\/-]+$/'],
            'email' => ['nullable', 'email', 'max:191'],
            'party_size' => ['required', 'integer', 'min:1', 'max:12'],
            'reservation_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'reservation_time' => [
                'required',
                'date_format:H:i',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! is_string($value) || $this->reservation_date !== now()->format('Y-m-d')) {
                        return;
                    }
                    if (Carbon::createFromFormat('Y-m-d H:i', $this->reservation_date.' '.$value)?->isPast() ?? false) {
                        $fail(__('pub_theme::reservation.time_in_past'));
                    }
                },
            ],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function submit(): void
    {
        $key = 'reservation:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('form', __('pub_theme::reservation.too_many', ['minutes' => (int) ceil(RateLimiter::availableIn($key) / 60)]));

            return;
        }

        $data = $this->validate();
        RateLimiter::hit($key, 600);

        unset($data['restaurantSlug']);
        if ($this->restaurantId !== null) {
            $restaurant = XotData::make()->getTenantClass()::query()->whereKey($this->restaurantId)->where('is_active', true)->first();
        } else {
            $restaurant = app(FindPublicRestaurantAction::class)->execute($this->restaurantSlug);
        }
        abort_if($restaurant === null, 404);

        app(CreatePublicReservationAction::class)->execute($data, $restaurant);

        $this->sent = true;
        $this->reset(['customer_name', 'phone', 'email', 'party_size', 'reservation_time', 'notes']);
        $this->party_size = 2;
    }
};
?>

<section class="section">
    <div class="container">
        @php($titleTag = ($heading ?? 'h2') === 'h1' ? 'h1' : 'h2')
        @if (filled($title ?? null))<{{ $titleTag }}>{{ $title }}</{{ $titleTag }}>@endif
        @if (filled($lead ?? null))<p class="lead">{{ $lead }}</p>@endif
        @if (($item ?? null) !== null)
            <p class="hint reservation-context">{{ __('pub_theme::reservation.at_restaurant', ['name' => $item->name]) }}</p>
        @endif

        @volt('reservation-form')
        <div class="form-card" style="margin-top:2rem">
            @if ($sent)
                <p class="hint" role="status">{{ $success_message ?? __('pub_theme::reservation.success') }}</p>
            @else
                <form wire:submit="submit">
                    @error('form')<p class="hint" role="alert">{{ $message }}</p>@enderror
                    <div class="form-grid">
                        @if ($restaurantId === null)
                            <div class="field full">
                                <label for="reservation_restaurant">{{ __('pub_theme::reservation.restaurant') }}</label>
                                <select id="reservation_restaurant" wire:model="restaurantSlug" required @error('restaurantSlug') aria-invalid="true" @enderror>
                                    <option value="">{{ __('pub_theme::reservation.restaurant_placeholder') }}</option>
                                    @foreach ($this->restaurantOptions() as $optionSlug => $optionName)
                                        <option value="{{ $optionSlug }}">{{ $optionName }}</option>
                                    @endforeach
                                </select>
                                @error('restaurantSlug')<p class="hint">{{ $message }}</p>@enderror
                            </div>
                        @endif
                        <div class="field">
                            <label for="reservation_customer_name">{{ __('pub_theme::reservation.customer_name') }}</label>
                            <input id="reservation_customer_name" type="text" wire:model="customer_name" autocomplete="name" required>
                            @error('customer_name')<p class="hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="reservation_phone">{{ __('pub_theme::reservation.phone') }}</label>
                            <input id="reservation_phone" type="tel" wire:model="phone" autocomplete="tel" inputmode="tel" required>
                            @error('phone')<p class="hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="reservation_email">{{ __('pub_theme::reservation.email') }}</label>
                            <input id="reservation_email" type="email" wire:model="email" autocomplete="email">
                            @error('email')<p class="hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <span class="label" id="reservation_party_size_label">{{ __('pub_theme::reservation.party_size') }}</span>
                            <div class="stepper" role="group" aria-labelledby="reservation_party_size_label">
                                <button type="button" class="stepper-btn" wire:click="decrementParty" @disabled($party_size <= 1) aria-label="{{ __('pub_theme::reservation.decrease_guests') }}">&minus;</button>
                                <output class="stepper-value" aria-live="polite">{{ $party_size }} {{ $party_size === 1 ? __('pub_theme::reservation.person') : __('pub_theme::reservation.people') }}</output>
                                <button type="button" class="stepper-btn" wire:click="incrementParty" @disabled($party_size >= 12) aria-label="{{ __('pub_theme::reservation.increase_guests') }}">+</button>
                            </div>
                            @error('party_size')<p class="hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="reservation_date">{{ __('pub_theme::reservation.reservation_date') }}</label>
                            <input id="reservation_date" type="date" wire:model="reservation_date" min="{{ now()->format('Y-m-d') }}" required>
                            @error('reservation_date')<p class="hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field">
                            <label for="reservation_time">{{ __('pub_theme::reservation.reservation_time') }}</label>
                            <input id="reservation_time" type="time" wire:model="reservation_time" required>
                            @error('reservation_time')<p class="hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field full">
                            <label for="reservation_notes">{{ __('pub_theme::reservation.notes') }}</label>
                            <textarea id="reservation_notes" wire:model="notes" placeholder="{{ __('pub_theme::reservation.notes_placeholder') }}"></textarea>
                            @error('notes')<p class="hint">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <p class="hint" style="margin:1rem 0">{{ __('pub_theme::reservation.hint') }}</p>
                    <button class="button" type="submit" wire:loading.attr="disabled">{{ $submit_label ?? __('pub_theme::reservation.submit') }}</button>
                </form>
            @endif
        </div>
        @endvolt
    </div>
</section>
