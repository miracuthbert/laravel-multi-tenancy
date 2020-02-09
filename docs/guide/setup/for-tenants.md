# For Tenant Models

You can add tenancy to models that are related to a tenant by adding the `ForTenants` trait.

## Existing Model

To setup a model tenancy in an existing model, add the `ForTenants` trait to the model.

### Creating

Create a migration to update the table with the code below in the `up` method: 

```php
$table->forTenant();
```

### Dropping

In the `down` method of the migration use the code below:

```php
$table->dropForTenant();
```

These two code blocks above add and drop corresponding tenant columns to the specified table.

## New Model 

For a new model, you can just use the command:

```
php artisan tenancy:model {name} --for-tenant
```

The `{name}` is the name of the model.

::: warning
You must pass the `--for-tenant` option
:::

### Options
- `-m`: Creates a migration for the model and places it in the folder corresponding to the default driver set.

::: tip
All the options available for the `make:model` can be used here
:::

## Migrations

The migration for a given model needs to be placed in a directory corresponding to the default driver.

For `multi` database drivers all the migrations should be placed in `migrations/tenant` folder.

::: tip
Use the `tenancy:model` or `tenancy:migration` command with the `--for-tenant` option and the migration path will be resolved for you
:::
