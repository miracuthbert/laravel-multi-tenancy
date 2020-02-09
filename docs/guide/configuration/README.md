# Configuration

To customize the package usage, copy the package config to your local config by using the publish command:

```
php artisan vendor:publish --tag=tenancy-config
```

The package config will be published to `config` folder under the name `tenancy.php`.

::: tip
You can skip the step above if you have run the: `php artisan tenancy:setup` command
:::

The package includes configuration for:  

## Default driver

Use by the `default` key to set the tenancy driver to use. The default the driver is `single`.

> Note that the driver should have been registered in the drivers option of the config file.

## Model

This sets options for the tenant model to use.

You can set the `class`, `key` and `route_key`.

- `class` is the model to set as tenant
- `key` is the column used to query against the set tenant model
- `route_key` is the value to be retrieved directly from the request in case none is available.

## Users

This sets options for the users related to the tenant model.

Here you can also specify the: 

- `model` used for tenant user relationship and the rest will be resolved. 

- `tenant_user` It will be used as the `tenant users` table name in case you don't follow Laravel's pivot table naming convention.  

## Connection

Sets options for database connection. Only applicable for `multi-database` driver.

## Routes

You can publish routes by running: `php artisan vendor:publish --tag=tenancy-routes`

You can specify the route: `prefix`, `as` (route names prefix), `namespace`, `middleware` and route `file`.

### Routes file

All tenant routes by default are placed in the `routes/tenant.php` file.

### Middleware

Routes middleware are grouped into: `before` and `tenant`.

`before` middleware are the ones to apply before the `tenant` middleware is executed. eg. `web`, `auth`

`set_tenant` middleware configures the middleware that resolves the current tenant.

It has the following options:

- `in_tenant`: If set `true`, it determines whether the current user is authorized to access tenant.
- `in_header`: If set `true`, it determines whether the current tenant should be fetched from header.

`tenant` middleware are the ones to apply along with the default package middleware.

::: tip
Tenant routes by default are set to use the `web` middleware, but basically they can be stateless.
::: 

## Redirect

By default you can use the `tenant.switch` route passing in a `model` to handle switching between tenants.

The config for this route lies in the key `redirect`.

You can set the following options for this route:

- `url`: The url to redirect to when a tenant is resolved. It must be under tenant routes. See [Routes file](#routes-file)
- `abort`: Set `true` if you would like to abort incase a tenant is not found or user is not authorized to access tenant.
- `fallback_url`: The url to redirect user to if tenant was not found. Works only if the `abort` option is set to `true`.
- `middleware`: The middleware to apply to protect this route.

:::tip
It is not a must to use this option. You can set your own tenant switching functionality.
:::

## Store

This controls the key and driver used to put and retrieve the tenant currently used.

It has two options:
-`key`: Prefix of used to identify a the user's current tenant.
-`driver`: The type of storage to keep the user's tenant. The default is `session`.
    
Supported store drivers are: `session`, `cache`, `cookie`

## Console

Configure the console options to be used by the tenant.

### Commands

You can add commands to be registered for use by the tenant. 

They are grouped into two options:

- `migrator`: For migration commands.
- `db`: For database commands.

See [Console](/guide/console/) to learn more on console.

## Drivers

You can add custom drivers here. By default two drivers are included: `single` and `multi`.

See [Drivers](/guide/drivers/) to learn more on drivers.
