<?php

namespace OpenFeature\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use OpenFeature\OpenFeatureAPI;

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
            $provider = ProviderBuilder::fromConfig(Config::get('openfeature.provider'));
            OpenFeatureAPI::getInstance()->setProvider($provider);

            // setup laravel logger
            OpenFeatureAPI::getInstance()->setLogger( Log::driver() );

            // setup client from config
            return OpenFeature::fromConfig(Config::get('openfeature.default') );
        });
    }
}
