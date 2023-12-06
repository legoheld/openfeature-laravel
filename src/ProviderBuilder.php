<?php

namespace OpenFeature\Laravel;

use Illuminate\Support\Facades\Cache;


class ProviderBuilder
{


    public static function fromConfig(array $config)
    {
        $type = $config['type'] ?? null;

        // not type will not setup any provider
        if (empty($type)) return;

        if (method_exists(self::class, $type)) {
            $provider = self::{$type}($config);

            if (isset($provider)) return $provider;

            throw new \Exception("Could not build provider from $config");
        } else {
            throw new \Exception("$type provider is not supported yet.");
        }
    }

    protected static function cloudbee($config)
    {
        self::validateClass('OpenFeature\Providers\Flagd\CloudBeesProvider', 'composer require open-feature/cloudbees-provider');

        return new \OpenFeature\Providers\Flagd\CloudBeesProvider($config['apiKey'], $config['options']);
    }

    protected static function flagd($config)
    {
        self::validateClass('OpenFeature\Providers\Flagd\FlagdProvider', 'composer require open-feature/flagd-provider');
        self::validateClass('GuzzleHttp\Client', 'composer require guzzlehttp/guzzle');
        self::validateClass('GuzzleHttp\Psr7\HttpFactory', 'composer require guzzlehttp/psr7');

        $config['httpConfig'] = [
            'client' => new \GuzzleHttp\Client(),
            'requestFactory' => new \GuzzleHttp\Psr7\HttpFactory(),
            'streamFactory' => new \GuzzleHttp\Psr7\HttpFactory(),
        ];

        return new \OpenFeature\Providers\Flagd\FlagdProvider($config);
    }

    protected static function flipt($config)
    {
        self::validateClass('OpenFeature\Providers\Flipt\FliptProvider', 'composer require open-feature/flipt-provider');

        $fliptProvider = new \OpenFeature\Providers\Flipt\FliptProvider($config['host'], $config['apiToken'], $config['namespace']);

        if ($config['caching']) {
            return new \OpenFeature\Providers\Flipt\CacheProvider($fliptProvider, Cache::store());
        }

        return $fliptProvider;
    }

    protected static function split($config)
    {
        self::validateClass('OpenFeature\Providers\Split\SplitProvider', 'composer require open-feature/split-provider');

        return new \OpenFeature\Providers\Split\SplitProvider($config['apiKey'], $config['options']);
    }




    protected static function validateClass($class, $package)
    {
        if (!class_exists($class)) {
            throw new \Exception("Provider class $class not installed. Please install package with $package.");
        }
    }
}
