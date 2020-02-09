# Laravel Multi-Tenancy

A single database and multi-database multi-tenancy package for Laravel 5.8 and up.

For the full installation, configuration and usage, see the [Documentation](miracuthbert.github.io/laravel-multi-tenancy).

## Installation

You can install the package via composer:

```
composer require miracuthbert/laravel-multi-tenancy
```

## Setup

The package takes advantage of Laravel Auto-Discovery, so it doesn't require you to manually add the ServiceProvider.

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Miracuthbert\Multitenancy\LaravelMultiTenancyServiceProvider::class
```

Run the following command in your console: `php artisan tenancy:setup`

This will setup:

- The package config file
- The tenant routes file
- TenantDatabaseSeeder (by default used only by the `multi` driver)
- Plus a tenant `model` and `migration` file if you passed a model name to the `--model` option. See below for more.

> You need to setup some of the required keys first in the `config/tenancy.php` file before migrating the database

See the [Documentation](miracuthbert.github.io/laravel-multi-tenancy).

## Security Vulnerabilities

If you discover a security vulnerability, please send an e-mail to Cuthbert Mirambo via [miracuthbert@gmail.com](mailto:miracuthbert@gmail.com). All security vulnerabilities will be promptly addressed.

## Credits

- [Cuthbert Mirambo](https://github.com/miracuthbert)

## License

Laravel Multi-Tenancy is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).