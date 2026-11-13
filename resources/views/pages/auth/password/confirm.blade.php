<?php

declare(strict_types=1);

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

use function Laravel\Folio\{middleware, name};

/*
 * Conferma della password prima di un'operazione delicata (middleware `password.confirm`).
 */
middleware(['auth']);
name('password.confirm');

new class extends Component {
    #[Validate('required|current_password')]
    public string $password = '';

    public function confirm(): mixed
    {
        $this->validate();
        session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(url('/'.app()->getLocale()));
    }
};
?>
<x-layouts.main>
    <section class="section auth">
        <div class="container auth__container">
            <p class="eyebrow">{{ __('pub_theme::auth.login.eyebrow') }}</p>
            <h1>{{ __('pub_theme::auth.confirm.title') }}</h1>
            <p class="lead">{{ __('pub_theme::auth.confirm.lead') }}</p>

            @volt('auth.password.confirm')
                <form wire:submit="confirm" class="form-card form-grid" novalidate>
                    <div class="field">
                        <label for="password">{{ __('pub_theme::auth.fields.password') }}</label>
                        <input id="password" type="password" wire:model="password" autocomplete="current-password" required autofocus @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        @error('password')<p id="password-error" class="field__error" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="button button--primary" wire:loading.attr="disabled">{{ __('pub_theme::auth.confirm.submit') }}</button>
                </form>
            @endvolt
        </div>
    </section>
</x-layouts.main>
