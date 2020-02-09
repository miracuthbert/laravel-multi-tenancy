# Drivers

By default the package comes with two drivers: `single` and `multi`.

The `default` driver is `single`.

## single

Uses the same database for each tenant and performs tenant operations via `scope` and `observer`.

## multi

Uses separate databases for each tenant and performs tenant operations via the specified `tenant connection`.

:::tip
Migrations for `multi` database driver reside under the `migrations/tenant` folder.
:::

## Custom drivers

You can add new drivers or extend the existing drivers.

### New drivers

To create a new driver you need to:

- Extend the `Miracuthbert\Multitenancy\Drivers\DriverAbstract` class.
- Implement the `Miracuthbert\Multitenancy\Drivers\TenantDriver` interface.

### Extending drivers

You can extend:

- The `Miracuthbert\Multitenancy\Drivers\Single\SingleDatabaseDriver` or
- The `Miracuthbert\Multitenancy\Drivers\Multi\MultiDatabaseDriver`

:::warning
Once you have created a custom driver do not forget to add it to the `drivers` key in the config. 
:::

See the [drivers](/guide/configuration/#drivers) section in configuration.
