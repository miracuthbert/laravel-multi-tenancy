# Package Setup

Run the following command in your console: `php artisan tenancy:setup`

This will setup:

- The package config file, `tenancy.php`
- The tenant routes file, `routes/tenant.php`
- TenantDatabaseSeeder (by default used only by the `multi` driver)
- Plus a tenant `model` and `migration` file if you passed a model name to the `--model` option. See below for more.

::: warning
You need to setup some of the required keys first in the `config/tenancy.php` file before migrating the database
:::

## Options

- `--multi`: Setup files required for `multi-database` tenancy
- `--model`: Create a new Eloquent based model class with all `tenant` functionality set
- `--columns-only`: Indicates if the created migration should only add tenant columns to an existing table (can only be used along with `--model` option)
- `--table`: Create a migration file with tenant columns only for an existing table
