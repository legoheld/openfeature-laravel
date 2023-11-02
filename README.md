# openfeature-laravel
Helpers to use OpenFeature with laravel



## Tests

Tests can be run in a docker container like this:

```bash
# install composer dependencies
docker run -v $PWD:/app -w /app composer install

# execute phpunit
docker run -v $PWD:/app -w /app --entrypoint vendor/bin/phpunit php:8-cli
docker run -v $PWD:/app -w /app -e XDEBUG_CONFIG="client_host=host.docker.internal discover_client_host=true" --entrypoint vendor/bin/phpunit registry.gitlab.lernetz.ch/docker/laravel:9-php8-fpm-node18-dev
```