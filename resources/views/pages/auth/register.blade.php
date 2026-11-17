<?php

declare(strict_types=1);

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Modules\Xot\Datas\XotData;

use function Laravel\Folio\{middleware, name};

/*
 * Registrazione dal sito (`/{lang}/auth/register`). Il modello utente e' quello del progetto
 * (XotData, connessione `user`): prima si usava App\Models\User sulla connessione dei dati e
 * l'unicita' dell'email veniva controllata sul database sbagliato.
 */
middleware(['guest']);
name('register');

new class extends Component {
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', Rule::unique(XotData::make()->getUserClass(), 'email')],
            'password' => ['required', 'min:8', 'same:passwordConfirmation'],
            'passwordConfirmation' => ['required'],
        ];
    }

    public function register(): mixed
    {
        $this->validate();

        $userClass = XotData::make()->getUserClass();
        $user = new $userClass;
        $user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ])->save();

        event(new Registered($user));
        Auth::login($user, true);
        session()->regenerate();

        return redirect()->intended(url('/'.app()->getLocale()));
    }
};
?>
<x-layouts.main>
    <section class="section auth">
        <div class="container auth__container">
            <p class="eyebrow">{{ __('pub_theme::auth.register.eyebrow') }}</p>
            <h1>{{ __('pub_theme::auth.register.title') }}</h1>
            <p class="lead">{{ __('pub_theme::auth.register.lead') }}</p>

            @volt('auth.register')
                <form wire:submit="register" class="form-card form-grid" novalidate>
                    @foreach ([['name', 'text', 'name'], ['email', 'email', 'email'], ['password', 'password', 'new-password'], ['passwordConfirmation', 'password', 'new-password']] as [$field, $type, $autocomplete])
                        <div class="field">
                            <label for="{{ $field }}">{{ __('pub_theme::auth.fields.'.$field) }}</label>
                            <input id="{{ $field }}" type="{{ $type }}" wire:model="{{ $field }}" autocomplete="{{ $autocomplete }}" required @error($field) aria-invalid="true" @enderror>
                            @error($field)<p class="field__error" role="alert">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                    <button type="submit" class="button button--primary" wire:loading.attr="disabled">{{ __('pub_theme::auth.register.submit') }}</button>
                    <p class="auth__links"><a href="{{ route('login') }}">{{ __('pub_theme::auth.register.login') }}</a></p>
                </form>
            @endvolt
        </div>
    </section>
</x-layouts.main>
