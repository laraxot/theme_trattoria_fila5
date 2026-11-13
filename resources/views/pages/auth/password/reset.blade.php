<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

use function Laravel\Folio\{middleware, name};

/*
 * Richiesta del link per reimpostare la password (`/{lang}/auth/password/reset`).
 * Il messaggio di conferma e' lo stesso sia che l'email esista sia che no: non si rivela chi e' registrato.
 * Massimo 3 richieste al minuto per email e IP.
 */
middleware(['guest']);
name('password.request');

new class extends Component {
    #[Validate('required|email')]
    public string $email = '';

    public bool $sent = false;

    public function sendResetPasswordLink(): void
    {
        $this->validate();

        $key = 'password-reset:'.Str::lower($this->email).'|'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('email', __('auth.throttle', ['seconds' => RateLimiter::availableIn($key)]));

            return;
        }
        RateLimiter::hit($key, 60);

        try {
            $status = Password::broker()->sendResetLink(['email' => $this->email]);
        } catch (\Throwable $e) {
            // Invio della mail fallito (SMTP, template): lo si registra e si avvisa l'utente invece di non mostrare nulla.
            report($e);
            $this->addError('email', __('pub_theme::auth.forgot.failed'));

            return;
        }
        if ($status === Password::RESET_THROTTLED) {
            $this->addError('email', __($status));

            return;
        }

        $this->sent = true;
    }
};
?>
<x-layouts.main>
    <section class="section auth">
        <div class="container auth__container">
            <p class="eyebrow">{{ __('pub_theme::auth.login.eyebrow') }}</p>
            <h1>{{ __('pub_theme::auth.forgot.title') }}</h1>
            <p class="lead">{{ __('pub_theme::auth.forgot.lead') }}</p>

            @volt('auth.password.reset')
                <div>
                    @if ($sent)
                        <div class="form-card alert alert--success" role="status">
                            <p>{{ __('pub_theme::auth.forgot.sent') }}</p>
                            <p class="auth__links"><a href="{{ route('login') }}">{{ __('pub_theme::auth.back_to_login') }}</a></p>
                        </div>
                    @else
                        <form wire:submit="sendResetPasswordLink" class="form-card form-grid" novalidate>
                            <div class="field">
                                <label for="email">{{ __('pub_theme::auth.fields.email') }}</label>
                                <input id="email" type="email" wire:model="email" autocomplete="email" required autofocus @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                                @error('email')<p id="email-error" class="field__error" role="alert">{{ $message }}</p>@enderror
                            </div>
                            <button type="submit" class="button button--primary" wire:loading.attr="disabled">{{ __('pub_theme::auth.forgot.submit') }}</button>
                            <p class="auth__links"><a href="{{ route('login') }}">{{ __('pub_theme::auth.back_to_login') }}</a></p>
                        </form>
                    @endif
                </div>
            @endvolt
        </div>
    </section>
</x-layouts.main>
