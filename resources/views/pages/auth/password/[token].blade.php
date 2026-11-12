<?php

declare(strict_types=1);

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

use function Laravel\Folio\{middleware, name};

/*
 * Nuova password dal link ricevuto per email (`/{lang}/auth/password/{token}?email=...`).
 * Dopo il cambio l'utente e' gia' dentro e torna alla home nella sua lingua.
 */
middleware(['guest']);
name('password.reset');

new class extends Component {
    #[Locked]
    public string $token = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8|same:passwordConfirmation')]
    public string $password = '';

    public string $passwordConfirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $email = request()->query('email', '');
        $this->email = \is_string($email) ? $email : '';
    }

    public function resetPassword(): mixed
    {
        $this->validate();

        $status = Password::broker()->reset(
            ['token' => $this->token, 'email' => $this->email, 'password' => $this->password],
            function ($user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
                Auth::guard()->login($user);
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return null;
        }

        session()->regenerate();
        session()->flash('status', __($status));

        return redirect(url('/'.app()->getLocale()));
    }
};
?>
<x-layouts.main>
    <section class="section auth">
        <div class="container auth__container">
            <p class="eyebrow">{{ __('pub_theme::auth.login.eyebrow') }}</p>
            <h1>{{ __('pub_theme::auth.reset.title') }}</h1>
            <p class="lead">{{ __('pub_theme::auth.reset.lead') }}</p>

            @volt('auth.password.token')
                <form wire:submit="resetPassword" class="form-card form-grid" novalidate>
                    <div class="field">
                        <label for="email">{{ __('pub_theme::auth.fields.email') }}</label>
                        <input id="email" type="email" wire:model="email" autocomplete="email" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')<p id="email-error" class="field__error" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="password">{{ __('pub_theme::auth.fields.new_password') }}</label>
                        <input id="password" type="password" wire:model="password" autocomplete="new-password" required autofocus aria-describedby="password-hint @error('password') password-error @enderror" @error('password') aria-invalid="true" @enderror>
                        <p id="password-hint" class="hint">{{ __('pub_theme::auth.fields.password_hint') }}</p>
                        @error('password')<p id="password-error" class="field__error" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="passwordConfirmation">{{ __('pub_theme::auth.fields.passwordConfirmation') }}</label>
                        <input id="passwordConfirmation" type="password" wire:model="passwordConfirmation" autocomplete="new-password" required>
                    </div>
                    <button type="submit" class="button button--primary" wire:loading.attr="disabled">{{ __('pub_theme::auth.reset.submit') }}</button>
                </form>
            @endvolt
        </div>
    </section>
</x-layouts.main>
