------
**Buckhill-Petshop** This task requires me to create an API that provides the necessary endpoints and HTTP request
methods
to satisfy the needs of the FE team for them to be able to build the UI.

- Olarewaju Mojeed: **[github.com/Lowkey1729](https://github.com/Lowkey1729)**

## Table of Contents

- [Run via Docker](#run-via-docker)
- [Swagger Documentation](#swagger-documentation)
- [Formatting](#formatting)
- [Testing](#testing)
- [PHPStan](#phpstan)
- [Code Analysis(Php-insights)](#code-analysisphp-insights)

## Get Started

> **Requires [PHP 8.2+](https://php.net/releases/)**

First, Clone the repository into you your local environment

```bash
git clone  https://github.com/Lowkey1729/buckhill-petshop.git
```

## Run Via Docker

Simply run the **make** command

```bash
make
```

The **make** command runs the following automatically, this means that the
**make** command is sufficient to run all of this on the fly

```bash
docker compose build
docker compose up -d --remove-orphans
docker compose exec app composer install --ignore-platform-reqs
docker compose exec app cp .env.example .env
docker compose exec app cp .env.testing.example .env.testing
docker compose exec app php artisan config:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link
docker compose exec app chmod -R 777 storage bootstrap/cache
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan test

```

### localhost.

http://localhost:8000 will be available to access the app.

## Swagger Documentation

### Install npm and build views outside docker

```bash
    npm install && npm run dev
```

### Route to the documentation is

```php
    http://localhost:8000/swagger
```

## Formatting

To run the PSR12 format test, run

```bash
./vendor/bin/pint
```

## Testing

To run tests, run

```bash
php artisan test
```

## PHPStan

To run PHP stan, run

```bash
./vendor/bin/phpstan analyse
```

## Code Analysis(Php-insights)

```bash
./vendor/bin/phpinsights 
```


