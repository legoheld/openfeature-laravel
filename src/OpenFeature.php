<?php

namespace OpenFeature\Laravel;

use Closure;
use OpenFeature\OpenFeatureClient;
use OpenFeature\OpenFeatureAPI;
use OpenFeature\interfaces\provider\Provider;
use OpenFeature\Laravel\Mappers\DefaultMapper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;

class OpenFeature
{

    protected OpenFeatureClient $client;

    public function __construct(OpenFeatureClient $client )
    {
        $this->client = $client;
    }


    function client(string $name)
    {
        return self::fromConfig($name, null );
    }


    function provider() {
        return OpenFeatureAPI::getInstance()->getProvider();
    }


    function for(mixed $scope)
    {
        return self::fromConfig($this->client->getMetadata()->getName(), $scope );
    }


    function boolean($flag, $default = false)
    {
        return $this->client->getBooleanValue($flag, $default);
    }

    function string($flag, $default = '')
    {
        return $this->client->getStringValue($flag, $default);
    }

    function float($flag, $default = 0)
    {
        return $this->client->getFloatValue($flag, $default);
    }

    function integer($flag, $default = 0)
    {
        return $this->client->getIntegerValue($flag, $default);
    }

    function object($flag, $default = [])
    {
        return $this->client->getObjectValue($flag, $default);
    }

    /**
     * Aliases for penannt compatibility
     */
    function active($flag)
    {
        return $this->boolean($flag);
    }
    function allAreActive($flags)
    {
        return Collection::make($flags)
            ->every(fn ($flag) => $this->boolean($flag));
    }
    function someAreActive($flags)
    {
        return Collection::make($flags)
            ->some(fn ($flag) => $this->boolean($flag));
    }
    function inactive($flag)
    {
        return !$this->boolean($flag);
    }
    function allAreInactive($flags)
    {
        return Collection::make($flags)
            ->every(fn ($flag) => !$this->boolean($flag));
    }
    function someAreInactive($flags)
    {
        return Collection::make($flags)
            ->some(fn ($flag) => !$this->boolean($flag));
    }
    function when($flag, Closure $whenActive, Closure $whenInactive = null)
    {
        if ($this->boolean($flag)) {
            return $whenActive($this->boolean($flag), $this);
        }
        if ($whenInactive !== null) {
            return $whenInactive($this);
        }
    }
    public function unless($flag, Closure $whenInactive, Closure $whenActive = null)
    {
        return $this->when($flag, $whenActive ?? fn () => null, $whenInactive);
    }



    public static function fromConfig(string $name, mixed $scope = null ): self
    {

        $client = OpenFeatureAPI::getInstance()->getClient($name, '');

        // get client config to set the corresponding context
        $configPath = 'openfeature.clients.' . $name;
        if (!Config::has($configPath)) throw new \Exception("No open feature client config found at " . $configPath);

        $mapperClass = Config::get($configPath . '.mapper');
        $mapper = isset($mapperClass) ? App::make($mapperClass) : new DefaultMapper();

        $client->setEvaluationContext($mapper->map(Config::get($configPath . '.context', []), $scope));

        

        return new self($client );
    }
}
