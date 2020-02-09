# Usage

## Routing

All tenant routes should be placed within the `routes/tenant.php` file or in the file set within the package config.

::: warning
Tenant routes follow the same structure as other routes
:::

::: tip
Separating tenant routes simplifies route binding and makes it much easier to know which routes are for tenants.
:::

See [Routes](/guide/configuration/#routes) section in configuration.

## Switching Tenants

By default there is a `tenant.switch` route that expects a `tenant` as a parameter.

```php
// Using a model 'Team'

route('tenant.switch', $team);

url('/tenant/switch/', $team->id);
```

It has all the functionality set for switching between tenants and remembering the current user's selected tenant.

::: tip
The parameter passed to the `tenant.switch` route should match the `route_key` option of the `model` key in config
:::

See [Model](/guide/configuration/#model) section in configuration.

## Getting Tenant Instance

You can get the current tenant instance in different ways. 

The value returned will be a corresponding model instance of the model class set within the config. 

### Via request macro

```php
// via request helper
request()->tenant()

// via a variable with instance of request
$request->tenant()
```

### Via facade or helper

```php
// via tenant helper
tenant()

// via tenancy facade
Tenancy::tenant()
```

::: warning
The current tenant instance can only be accessed under `tenant` middleware protected areas (or routes)
:::

## CRUD Operations

Once a tenant model and for-tenant model has been set, you can perform all CRUD operations normally.

::: warning
Models that do not directly relate to the tenant model will still need to call the related relationships.
:::

### Tenant CRUD Operations

`Tenant` relationships are handled automatically.

```php

$projects = Project::create([
    'name' => 'Project 1'
]);

$projects = Project::get();

```

### Normal CRUD Operations

To perform CRUD operations on models with `ForTenants` trait can be done by 
adding `withoutForTenants` scope when fetching records associated with that model.

::: warning
This only applies if the `single` database driver is used.
:::

```php
$projects = Project::withoutForTenants()->get();
```

::: tip
This comes in handy for example in: admin or other non-tenant operations
:::

## Tenant File Storage

To store or fetch files for a specific tenant just pass the value `tenant` to the `disk` method of the storage instance.

`tenant` is a dynamic disk that extends the app's default filesystem driver and separates each tenant files into folders while using the same storage space.

```php
// specify `tenant` disk when you want to use it for a tenant

Storage::disk('tenant')->{methods}

// putting a file in the tenant's root folder
$path = Storage::disk('tenant')->putFile('/', $upload)

// putting a file in the tenant's sub folder
$path = Storage::disk('tenant')->putFile('/sub', $upload)
```

::: tip
Once you hit tenant routes the `tenant` disk will be setup automatically, you only have to specify it to use it
:::
