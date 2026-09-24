<?php

declare(strict_types=1);

namespace Themes\Trattoria;

use Illuminate\Support\ServiceProvider;
use Modules\Xot\Providers\XotBaseServiceProvider as BaseServiceProvider;

class TrattoriaServiceProvider extends BaseServiceProvider
{
    public string $name = 'Trattoria';

    protected string $theme_dir = __DIR__;

    protected string $theme_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'trattoria');

        $this->publishes([
            __DIR__.'/../public' => public_path('themes/trattoria'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../resources/css' => public_path('css/trattoria'),
        ], 'css');

        $this->publishes([
            __DIR__.'/../resources/js' => public_path('js/trattoria'),
        ], 'js');

        $this->publishes([
            __DIR__.'/../database/seeds' => database_path('seeds'),
        ], 'seeds');
    }
}