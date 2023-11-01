<?php namespace OpenFeature;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use OpenFeature;


class OpenFeatureServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes( [
            __DIR__.'/../config/config.php' => config_path( 'openfeature.php' ),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OpenFeature::class, function (Application $app) {
            return new OpenFeature( OpenFeature::clientFromConfig( Config::get( 'openfeature.default' ) ) );
        });
    }
}
