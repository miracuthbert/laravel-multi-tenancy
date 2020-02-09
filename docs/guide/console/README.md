# Console

There are two sets of commands bundled with the package: `tenancy` and `tenants`

## Tenancy Commands

Tenancy commands manage the package by default. They are:

- `tenancy:setup`: Used for setting up the package
- `tenancy:model`: Used for creating models specifically for tenants
- `tenancy:migration`: Used for creating tenant specific migrations
- `tenancy:users`: Used for creating the tenant user migration file

## Tenants Commands

These are used for setting up a tenant. They simplify most of the work done when a tenant is created.

They handle operations like migrating a tenants database, seed it, rollback migrations and more.

The included commands are:

- `tenants:migrate`: Used to run tenant migrations
- `tenants:seed`: Used to seed tenant databases
- `tenants:rollback`: Rollback tenant migrations
- `tenants:refresh`: Reset and re-run all tenant migrations
- `tenants:reset`: Rollback all database migrations for tenants

You can define your own tenant based commands and the register them in the `console` option of config.

The accepted commands are:

### migrator
 
These are migration based commands. Basically they deal with the database schema.

The command should accept instances of: 

- `Illuminate\Database\Migrations\Migrator` and 
- `Miracuthbert\Multitenancy\Database\TenantDatabaseManager`

### db
 
These are commands that interact with the database records such as `tenants:seed` command. 

The command should accept instances: 

- `Illuminate\Database\ConnectionResolverInterface` and 
- `Miracuthbert\Multitenancy\Database\TenantDatabaseManager`
