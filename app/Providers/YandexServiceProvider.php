<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class YandexServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/yandex.php', 'yandex'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/yandex.php' => config_path('yandex.php'),
        ], 'config');
    }
}
