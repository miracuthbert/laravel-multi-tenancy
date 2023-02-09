<?php

namespace Miracuthbert\Multitenancy;

use Illuminate\Database\Schema\Blueprint;
use Miracuthbert\Multitenancy\Cache\TenantCacheManager;
use Miracuthbert\Multitenancy\Console\MigrateReset;
use Miracuthbert\Multitenancy\Console\MigrationCreator;
use Miracuthbert\Multitenancy\Console\TenancyMigrateMake;
use Miracuthbert\Multitenancy\Console\TenancyModelMake;
use Miracuthbert\Multitenancy\Console\TenancySetup;
use Miracuthbert\Multitenancy\Console\TenancyUsers;
use Miracuthbert\Multitenancy\Http\Middleware\SetTenant;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Miracuthbert\Multitenancy\Console\Migrate;
use Miracuthbert\Multitenancy\Console\MigrateRefresh;
use Miracuthbert\Multitenancy\Console\MigrateRollback;
use Miracuthbert\Multitenancy\Console\Seed;

class LaravelMultiTenancyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();

        $this->app->register(EventServiceProvider::class);

        $this->app->register(RouteServiceProvider::class);

        $this->app->singleton(TenancyDriver::class, function ($app) {
            return new TenancyDriver($app);
        });

        $this->app->rebinding('events', function ($app, $dispatcher) {
            if (!$app->resolved(TenancyDriver::class)) {
                return;
            }

            $driver = ($app[TenancyDriver::class])->getDriver();

            if (method_exists($driver, 'setDispatcher')) {
                $driver->setDispatcher($dispatcher);
            }
        });

        // tenant columns
        $this->registerTenantColumns();

        $this->registerCreator();

        // migration commands
        $migratorCommands = array_merge([
            Migrate::class,
            MigrateRefresh::class,
            MigrateRollback::class,
            MigrateReset::class,
        ], config('tenancy.console.commands.migrator'));

        // db commands
        $dbCommands = array_merge([
            Seed::class,
        ], config('tenancy.console.commands.db'));

        // register tenant commands
        $this->commands($this->registerTenantCommands($migratorCommands, $dbCommands));

        // register command-line only commands
        $this->registerPackageConsoleCommands();

        if ($this->app->resolved(Manager::class)) {

            // tenant cache manager
            $this->app->extend('cache', function () {
                return new TenantCacheManager($this->app);
            });
        }

        $this->app->singleton(Tenancy::class, function ($app) {
            return new Tenancy();
        });

        $this->app->alias(Tenancy::class, 'tenancy');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerPublishing();

        $this->registerTenantManager();

        $this->registerTenantObserver();

        Request::macro('tenant', function () {
            return app(Manager::class)->getTenant();
        });

        Blade::if ('tenant', function () {
            return app(Manager::class)->hasTenant();
        });

        $this->registerMiddleware();
    }

    /**
     * Setup configuration for the package.
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-multi-tenancy.php', 'tenancy'
        );
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {

            // publish config
            $this->publishes([
                __DIR__ . '/../config/laravel-multi-tenancy.php' => config_path('tenancy.php'),
            ], MultiTenancy::TENANCY_CONFIG);

            // publish tenant routes
            $this->publishes([
                __DIR__ . '/../routes/tenant.php.stub' => config('tenancy.routes.file', base_path('routes/' . 'tenant.php')),
            ], MultiTenancy::TENANCY_ROUTES);

            // publish migrations
            $this->publishes([
                __DIR__ . '/../database/migrations/create_tenant_connections_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_tenant_connections_table.php'),
                __DIR__ . '/../database/migrations/tenant/README.md.stub' => database_path('migrations/tenant/' . 'README.md'),
            ], MultiTenancy::MULTI_DATABASE_MIGRATIONS);
        }
    }

    /**
     * Register the tenant's manager.
     *
     * @return void
     */
    protected function registerTenantManager()
    {
        $this->app->singleton(Manager::class, function () {
            return new Manager();
        });
    }

    /**
     * Register the tenant's observer.
     *
     * @return void
     */
    protected function registerTenantObserver()
    {
        $driver = (app(TenancyDriver::class))->getDriver();

        if ($driver->usesObserver()) {
            $observer = $driver->tenantObserver();

            $this->app->singleton($observer, function () use ($observer) {
                return new $observer (app(Manager::class)->getTenant());
            });
        }
    }

    /**
     * Register the package's middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $router = $this->app['router'];

        $router->model('tenant', config('tenancy.model.class'));

        // tenant additional middleware
        $setTenantAdditional = config('tenancy.routes.middleware.tenant');

        // tenant middleware
        $tenantMiddleware = array_merge([
            SetTenant::class,
        ], $setTenantAdditional);

        // add tenant middleware group
        $router->middlewareGroup('tenant', $tenantMiddleware);

        // get app's middleware priority list
        $mp = $router->middlewarePriority;

        // find index of substitute bindings middlware
        $index = Arr::first(array_keys($mp, SubstituteBindings::class));

        // map list to collection
        $collection = collect($mp);

        // add tenant middleware to collection
        $collection->splice($index, 0, $tenantMiddleware)->all();

        // reassign the middleware priority with the new list
        $router->middlewarePriority = $collection->all();
    }

    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator()
    {
        $this->app->singleton('tenancy.migration.creator', function ($app) {
            return new MigrationCreator($app['files'], $app->basePath('stubs'));
        });
    }

    /**
     * Register package console only commands.
     *
     * @param array $more
     * @return void
     */
    protected function registerPackageConsoleCommands($more = [])
    {
        if ($this->app->runningInConsole()) {
            $this->app->singleton('command.tenancy.migration', function ($app) {
                return new TenancyMigrateMake($app['tenancy.migration.creator'], $app['composer']);
            });

            $this->commands(array_merge([
                'command.tenancy.migration',
                TenancyModelMake::class,
                TenancySetup::class,
                TenancyUsers::class,
            ], $more));
        }
    }

    /**
     * Register tenant commands.
     *
     * @param $migratorCommands
     * @param $dbCommands
     * @return array
     */
    protected function registerTenantCommands($migratorCommands, $dbCommands)
    {
        $driver = (app(TenancyDriver::class))->getDriver();

        if (!$driver->hasDatabaseManager()) {
            return [];
        }

        $dbManager = $driver->databaseManager();

        foreach ($migratorCommands as $command) {
            $this->app->singleton($command, function ($app) use ($command, $dbManager) {
                return new $command($app['migrator'], $app->make($dbManager));
            });
        }

        foreach ($dbCommands as $command) {
            $this->app->singleton($command, function ($app) use ($command, $dbManager) {
                return new $command($app['db'], $app->make($dbManager));
            });
        }

        return array_merge($migratorCommands, $dbCommands);
    }

    /**
     * Register columns for tenant tables.
     *
     * @return void
     */
    public function registerTenantColumns()
    {
        Blueprint::macro('tenant', function ($options = []) {
            MultiTenancy::columns($this, $options);
        });

        Blueprint::macro('dropTenant', function () {
            MultiTenancy::dropColumns($this);
        });

        Blueprint::macro('tenantColumns', function () {
            MultiTenancy::tenantColumns($this);
        });

        Blueprint::macro('forTenant', function () {
            MultiTenancy::forTenantColumns($this);
        });

        Blueprint::macro('dropForTenant', function () {
            MultiTenancy::dropForTenantColumns($this);
        });
    }
}
