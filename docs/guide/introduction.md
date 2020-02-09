# Introduction

Laravel multi-tenancy is a single database and multi-database multi-tenancy package for Laravel 5.8 and up.

## How it works

The package comes with two drivers: `single` and `multi`. 

`single` uses a shared database while `multi` offers an isolated database for each tenant.

For a `single` database multi-tenancy, `scopes` and `observers` are used to perform CRUD operations on related models.

On the other hand `multi` database multi-tenancy uses a separate database connection where each database holds a specific tenant's records.

Both use specific routes that have a `tenant` middleware group that resolves the tenant and setups the required functionality based on the default driver.

## Supported features

### General

- Tenant specific routes (in `routes/tenant.php` or as specified in config)
- Isolated storage (filesystem disk) for each tenant
- Caching per-tenant (works with other cache drivers except `file` and `array`)

---

### Multi-database driver

- Isolated database for each tenant.
- Bulk and isolated schema migrations and seeding for tenants.
- Expressive, intuitive database ORM to reflect the correct tenant.


## Not currently supported

- Initial seeding for `single` database driver tenants
- Sub-domain handling
- Duo-driver usage (That is some tenants that use single-database and some using multi-database depending maybe on their subscription plan or other factor)
