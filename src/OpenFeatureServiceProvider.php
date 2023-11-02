<?php

namespace OpenFeature;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class OpenFeatureServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/openfeature.php' => config_path('openfeature.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('OpenFeature', function (Application $app) {

            // setup the provider
            $providers = new Providers();
            $providers->setup(Config::get('openfeature.provider'));

            return OpenFeature::fromConfig(Config::get('openfeature.default'));
        });
    }
}
