<?php

namespace MatteoGgl\Linnaeus;

use Illuminate\Support\ServiceProvider;

class LinnaeusServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/linnaeus.php', 'linnaeus');
    }

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'linnaeus');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/linnaeus.php' => config_path('linnaeus.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang'),
            ], 'translations');
        }
    }
}