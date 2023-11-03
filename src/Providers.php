<?php

namespace OpenFeature\Laravel;

use OpenFeature\OpenFeatureAPI;


class Providers
{


    public function setup($config)
    {
        $type = $config['type'] ?? null;

        // not type will not setup any provider
        if (empty($type)) return;

        if (method_exists($this, $type)) {
            $provider = $this->{$type}($config);

            if (isset($provider)) OpenFeatureAPI::getInstance()->setProvider($provider);
        } else {
            throw new \Exception("$type provider is not supported yet.");
        }
    }

    function cloudbee($config)
    {
        $this->validateClass('OpenFeature\Providers\Flagd\CloudBeesProvider', 'composer require open-feature/cloudbees-provider');
    
        return new \OpenFeature\Providers\Flagd\CloudBeesProvider($config['apiKey'], $config['options']);
    }

    function flagd($config)
    {
        $this->validateClass('OpenFeature\Providers\Flagd\FlagdProvider', 'composer require open-feature/flagd-provider');
        $this->validateClass('GuzzleHttp\Client', 'composer require guzzlehttp/guzzle');
        $this->validateClass('GuzzleHttp\Psr7\HttpFactory', 'composer require guzzlehttp/psr7');

        $config['httpConfig'] = [
            'client' => new \GuzzleHttp\Client(),
            'requestFactory' => new \GuzzleHttp\Psr7\HttpFactory(),
            'streamFactory' => new \GuzzleHttp\Psr7\HttpFactory(),
        ];

        return new \OpenFeature\Providers\Flagd\FlagdProvider($config);
    }

    function flipt($config)
    {
        $this->validateClass('OpenFeature\Providers\Flipt\FliptProvider', 'composer require open-feature/flipt-provider');

        return new \OpenFeature\Providers\Flipt\FliptProvider( $config['host'], $config['apiToken'], $config['namespace'] );
    }

    function split($config)
    {
        $this->validateClass('OpenFeature\Providers\Split\SplitProvider', 'composer require open-feature/split-provider');

        return new \OpenFeature\Providers\Split\SplitProvider($config['apiKey'], $config['options']);
    }




    function validateClass($class, $package)
    {
        if (!class_exists($class)) {
            throw new \Exception("Provider class $class not installed. Please install package with $package.");
        }
    }
}
