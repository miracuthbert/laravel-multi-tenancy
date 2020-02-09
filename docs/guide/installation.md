# Installation

You can install the package via composer:

```
composer require miracuthbert/laravel-multi-tenancy
```

The package takes advantage of Laravel Auto-Discovery, so it doesn't require you to manually add the ServiceProvider.

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Miracuthbert\Multitenancy\LaravelMultiTenancyServiceProvider::class
```
