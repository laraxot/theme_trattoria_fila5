<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

use function Laravel\Folio\{middleware, name};

/*
 * Accesso dal sito (`/{lang}/auth/login`): clienti, ristoratori e personale. Il link "Accedi"
 * dell'header porta qui; dopo l'accesso l'avatar dell'header elenca le aree di lavoro dell'utente.
 * L'evento Login lo emette gia' Auth::attempt(). Massimo 5 tentativi al minuto per email e IP.
 */
middleware(['guest']);
name('login');

new class extends Component {
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function authenticate(): mixed
    {
        $this->validate();

        $key = 'login:'.Str::lower($this->email).'|'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', __('auth.throttle', ['seconds' => RateLimiter::availableIn($key)]));

            return null;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key, 60);
            $this->addError('email', __('auth.failed'));

            return null;
        }

        RateLimiter::clear($key);
        session()->regenerate();

        return redirect()->intended(url('/'.app()->getLocale()));
    }
};
?>
<x-layouts.main>
    <section class="section auth">
        <div class="container auth__container">
            <p class="eyebrow">{{ __('pub_theme::auth.login.eyebrow') }}</p>
            <h1>{{ __('pub_theme::auth.login.title') }}</h1>
            <p class="lead">{{ __('pub_theme::auth.login.lead') }}</p>

            @volt('auth.login')
                <form wire:submit="authenticate" class="form-card form-grid" novalidate>
                    <div class="field">
                        <label for="email">{{ __('pub_theme::auth.fields.email') }}</label>
                        <input id="email" type="email" wire:model="email" autocomplete="email" required autofocus @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')<p id="email-error" class="field__error" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="password">{{ __('pub_theme::auth.fields.password') }}</label>
                        <input id="password" type="password" wire:model="password" autocomplete="current-password" required @error('password') aria-invalid="true" @enderror>
                        @error('password')<p class="field__error" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <label class="auth__remember">
                        <input type="checkbox" wire:model="remember">
                        <span>{{ __('pub_theme::auth.fields.remember') }}</span>
                    </label>
                    <button type="submit" class="button button--primary" wire:loading.attr="disabled">{{ __('pub_theme::auth.login.submit') }}</button>
                    <p class="auth__links">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">{{ __('pub_theme::auth.login.forgot') }}</a>
                        @endif
                        <a href="{{ route('register') }}">{{ __('pub_theme::auth.login.register') }}</a>
                    </p>
                </form>
            @endvolt
        </div>
    </section>
</x-layouts.main>
