# OpenFeature Laravel Package

This `open-feature/laravel` package provides an integration for feature flagging within Laravel applications using the OpenFeature SDK. It allows you to set up providers and clients, along with the ability to define custom mappers.

## Installation

Install the package via composer:

```bash
composer require open-feature/laravel
```

## Configuration

Publish the configuration file with:

```bash
php artisan vendor:publish --provider="OpenFeature\OpenFeatureServiceProvider"
```

This creates a config/openfeature.php file in your config directory for provider and client specification.


### Providers Setup

You can define your feature flag provider in the provider key. Supported providers include cloudbee, flagd, flipt, etc.

```php
'provider' => [
    'type' => 'flagd',
    'host' => 'localhost',
    'protocol' => 'http',
    'port' => 8013,
],
```

### Clients Setup

Define your clients in the clients array. Each client can have a custom static context and optionally a custom mapper class.

```php
'clients' => [
    'main' => [
        'context' => [
            'environment' => 'test',
        ],
        'mapper' => YourCustomMapper::class, // Optional custom mapper
    ],
    // Additional clients...
]
```

### Default Client

Specify the default client to use across your application.

```php
'default' => 'main',
```


## Usage

The OpenFeature facade provides a simple way to interact with feature flags. Here's how to use it:

```php

use OpenFeature\Facades\OpenFeature;

// Get boolean flag
$isEnabled = OpenFeature::boolean('feature_key', false);

// Get string flag
$message = OpenFeature::string('welcome_message', 'Hello, World!');

// Get float flag
$percentage = OpenFeature::float('rollout_percentage', 50.0);

// Get integer flag
$threshold = OpenFeature::integer('max_threshold', 100);

// Get object flag
$config = OpenFeature::object('feature_config', []);

```

### Client Selection

If you want to use a specific client, you can retrieve it by name:

```php
$client = OpenFeature::client('client_name');
```


### Scoped Evaluation

To evaluate flags within a specific scope, such as a user or session:

```php
$scopedClient = OpenFeature::for($user);
```


### Using with UserTrait

The UserTrait can be used in your User model to access the facade directly:

```php
<?php

namespace App\Models;

use OpenFeature\Traits\UserFeature;

class User 
{
    use UserFeature;

    // ....
}
```

This provides a convenient method $user->features() to evaluate feature flags for the user.

```php
$userFeatures = $user->features()->boolean( 'flag' );
```

Note that the UserTrait will automatically scope the user model for mapping see custom mapper.


## Custom Mapper


For custom mappers you have to implement the OpenFeature\Mappers\ContextMapper interface.
An example cloud look like this:
```php

<?php

namespace App\Mappers;

use OpenFeature\Mappers\ContextMapper;
use OpenFeature\interfaces\flags\EvaluationContext;


class CustomContextMapper implements ContextMapper
{
    /**
     * Transforms the given array context and scope into an EvaluationContext.
     * 
     * @param array $context The context as an associative array.
     * @param mixed $scope The scope which is injected by the OpenFeature::for( $scope ) method.
     * 
     * @return EvaluationContext The mapped evaluation context.
     */
    public function map(array $context, mixed $scope): EvaluationContext
    {
        // Here you can customize the mapping logic based on the $context array
        // and the $scope variable, which might be a user object, a request, etc.

        // add additional properties to context
        // is use the UserTrait, the user is automatically in the scope and you can do stuff like this
        $context['isAdmin'] = in_array( 'admin', $scope->roles );
        
        // return the targetKey for the evaluationcontext for example the unique id of the user
        return new EvaluationContext( $scope->getId(), $context);
    }
}

```

Remember to register your mapper in the `client` configuration array.




## Tests

Tests can be run in a docker container like this:

```bash
# install composer dependencies
docker run -v $PWD:/app -w /app composer install

# execute phpunit
docker run -v $PWD:/app -w /app --entrypoint vendor/bin/phpunit php:8-cli
docker run -v $PWD:/app -w /app -e XDEBUG_CONFIG="client_host=host.docker.internal discover_client_host=true" --entrypoint vendor/bin/phpunit registry.gitlab.lernetz.ch/docker/laravel:9-php8-fpm-node18-dev
```