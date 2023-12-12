<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class VkServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/vk.php', 'vk'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/vk.php' => config_path('vk.php'),
        ], 'config');
    }
}
