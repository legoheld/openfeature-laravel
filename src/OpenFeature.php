<?php

namespace OpenFeature;

use OpenFeature\OpenFeatureClient;
use OpenFeature\OpenFeatureAPI;
use OpenFeature\Mappers\DefaultMapper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class OpenFeature
{

    protected OpenFeatureClient $client;

    public function __construct(OpenFeatureClient $client)
    {
        $this->client = $client;
    }


    function client(string $name)
    {
        return self::fromConfig($name);
    }


    function for(mixed $scope)
    {
        return self::fromConfig($this->client->getMetadata()->getName(), $scope);
    }


    function boolean($flag, $default)
    {
        return $this->client->getBooleanValue($flag, $default);
    }

    function string($flag, $default)
    {
        return $this->client->getStringValue($flag, $default);
    }

    function float($flag, $default)
    {
        return $this->client->getFloatValue($flag, $default);
    }

    function integer($flag, $default)
    {
        return $this->client->getIntegerValue($flag, $default);
    }

    function object($flag, $default)
    {
        return $this->client->getObjectValue($flag, $default);
    }



    public static function fromConfig(string $name, mixed $scope = null): self
    {

        $client = OpenFeatureAPI::getInstance()->getClient($name, '');

        // get client config to set the corresponding context
        $configPath = 'openfeature.clients.' . $name;
        if (!Config::has($configPath)) throw new \Exception("No open feature client config found at " . $configPath);

        $mapperClass = Config::get($configPath . '.mapper');
        $mapper = isset($mapperClass) ? App::make($mapperClass) : new DefaultMapper();

        $client->setEvaluationContext($mapper->map(Config::get($configPath . '.context', []), $scope));

        return new self($client);
    }
}
