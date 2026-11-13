<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

use function Laravel\Folio\{middleware, name};

/*
 * Avviso "verifica la tua email" (`verification.notice`). Il pulsante rimanda il link;
 * l'evento Verified lo emette solo il link stesso, non il reinvio.
 */
middleware(['auth', 'throttle:6,1']);
name('verification.notice');

new class extends Component {
    public bool $resent = false;

    public function resend(): mixed
    {
        $user = Auth::user();
        if (! $user instanceof MustVerifyEmail || $user->hasVerifiedEmail()) {
            return redirect(url('/'.app()->getLocale()));
        }

        $user->sendEmailVerificationNotification();
        $this->resent = true;

        return null;
    }
};
?>
<x-layouts.main>
    <section class="section auth">
        <div class="container auth__container">
            <p class="eyebrow">{{ __('pub_theme::auth.login.eyebrow') }}</p>
            <h1>{{ __('pub_theme::auth.verify.title') }}</h1>
            <p class="lead">{{ __('pub_theme::auth.verify.lead') }}</p>

            @volt('auth.verify')
                <div class="form-card form-grid">
                    @if ($resent)
                        <p class="alert alert--success" role="status">{{ __('pub_theme::auth.verify.resent') }}</p>
                    @endif
                    <button type="button" wire:click="resend" class="button button--primary" wire:loading.attr="disabled">{{ __('pub_theme::auth.verify.submit') }}</button>
                    <form method="POST" action="{{ route('logout') }}" class="auth__links">
                        @csrf
                        <button type="submit" class="link-button">{{ __('pub_theme::auth.verify.logout') }}</button>
                    </form>
                </div>
            @endvolt
        </div>
    </section>
</x-layouts.main>
