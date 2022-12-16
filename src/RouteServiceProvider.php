<?php

namespace Miracuthbert\Multitenancy;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->namespace = config('tenancy.routes.namespace');
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapTenantRoutes();

        //
    }

    /**
     * Define the "tenant" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapTenantRoutes()
    {
        if(!File::exists(config('tenancy.routes.file'))) {
            return;
        }

        $middleware = array_merge(
            config('tenancy.routes.middleware.before', []), [
                'tenant'
            ],
            config('tenancy.routes.middleware.after', [])
        );

        Route::prefix(config('tenancy.routes.prefix'))
            ->middleware($middleware)
            ->namespace($this->namespace)
            ->as(config('tenancy.routes.as'))
            ->group(config('tenancy.routes.file'));
    }
}
