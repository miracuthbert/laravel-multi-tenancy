# Tenant Setup

## Existing Model

To setup an existing model as a tenant, add `IsTenant` trait, plus `ForSystem` if using a multi-database driver.

### Creating

Create a migration to update the intended table with the code below in the `up` method: 

```php
$table->tenant();
```

This will create a `uuid` column and `domain` column. The `uuid` value is automatically handled by the package.

::: tip
To disable the `domain` column from being added by pass an array of options to the macro with `domain` set to `false` 
:::

```php
$table->tenant(['domain' => false]);
```

### Dropping

In the `down` method of the migration:

```php
$table->dropTenant();
```

Alternatively, you can run the command:

```php
php artisan tenancy:migration {name} --table={table_name}
```

Switch `{name}` with the name of the migration file and `{table_name}` with the name of the `table` you want to update.

::: danger
Before running `php artisan migrate`, you must setup the `model` key and `users` key values in `config/tenancy.php`
:::

## New Model

You can use the command below to create a fresh tenant model:

```
php artisan tenancy:model {name}
```

The `{name}` being the name of the model (with the corresponding namespace).

::: tip
All the options available for the `make:model` command can be used here
:::

### Options
- `-m`: Creates a tenant specific migration file (overrides the default migration)
- `--multi`: Indicates if the generated file should be for a multi-database tenant
- `--columns-only`: Indicates if the created migration should only add tenant columns to an existing table (can only be used along with `--model` option)

::: warning
By default the model generated will have the functionality for the single driver unless `--multi` option passed.
:::

## Users

To setup tenant users run:

```php
php artisan tenancy:users {name}
```

Replace `{name}` with the name of the pivot table. Example for model `Team` with users will be `team_user`.

::: warning
The package uses Laravel's naming convention for pivot tables and resolving the relationships.
:::

See the [users](/guide/configuration/#users) section in configuration for more on users and the [drivers](/guide/drivers/) section for more on drivers.

## Seeding

Currently seeding functionality is supported for the `multi` database driver only.

Whenever a `tenant` is created their database can be seeded.

::: tip
To seed a tenant database, related seeders should be registered in the `TenantDatabaseSeeder`
:::
