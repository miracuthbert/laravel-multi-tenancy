<?php

namespace Miracuthbert\Multitenancy;

use Miracuthbert\Multitenancy\Traits\TenancyDriverTrait;

class Tenancy
{
    use TenancyDriverTrait;

    public function tenantMiddlewares($before = [], $after = [], $defaults = ['tenant'])
    {
        return array_merge(
            config('tenancy.routes.middleware.before', []),
            $before,
            config('tenancy.routes.middleware.tenant', []),
            $defaults,
            config('tenancy.routes.middleware.after', []),
            $after
        );
    }

    /**
     * Get tenant's model store key based on default driver.
     *
     * @return string
     */
    public function getTenantDriverStoreKey()
    {
        return (tenancy()->config()->storeDriver() === 'db') ? config('tenancy.model.db_key') : config('tenancy.model.key');
    }

    /**
     * Get an instance of the Tenant manager.
     *
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function manager()
    {
        return app(Manager::class);
    }

    /**
     * Get the tenant.
     *
     * @return mixed
     */
    public function tenant()
    {
        return $this->manager()->getTenant();
    }

    /**
     * Get an instance of the tenant config helper.
     *
     * @return TenantConfigHelper
     */
    public function config()
    {
        return new TenantConfigHelper();
    }

    /**
     * Get an instance of the Tenant store.
     *
     * @return TenantStore
     */
    public function store()
    {
        return new TenantStore();
    }

    /**
     * Get subdomain safe routes list.
     * 
     * @return array|null
     */
    public function subdomainSafeRoutes()
    {
        return tenancy()->config()->getOption('routes.subdomain_safe_routes');
    }

    /**
     * Determine if current route is a non-tenant based subdomain.
     * 
     * @return bool
     */
    public function subdomainSafeCheck()
    {
        $subdomainSafeRoutes = $this->subdomainSafeRoutes();

        return empty($subdomainSafeRoutes) ? false : request()->routeIs($subdomainSafeRoutes);
    }

    /**
     * Resolve the tenant.
     *
     * @param  int|string $value
     * @param  bool       $ignoreSubdomainCheck
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveTenant($value, $ignoreSubdomainCheck = false)
    {
        $model = $this->tenantModel();

        if (tenancy()->config()->getOption('routes.subdomain') && $ignoreSubdomainCheck == false) {
            $key = tenancy()->config()->getOption('routes.subdomain_key', 'domain');
        } else {
            $key = $this->getTenantDriverStoreKey();
        }

        return $model->where($key, $value)->first();
    }

    /**
     * Get a new instance of the Tenant model.
     *
     * @return mixed
     */
    public function tenantModel()
    {
        $config = config('tenancy');

        $model = new $config['model']['class'];

        return $model;
    }

    /**
     * Get a new instance of the tenant User model.
     *
     * @return mixed
     */
    public function tenantUserModel()
    {
        $config = config('tenancy');

        $model = new $config['users']['model'];

        return $model;
    }

    /**
     * Get the class used to handle database creation statements.
     *
     * @return mixed
     */
    public function databaseCreatorStatement()
    {
        $config = config('tenancy');

        $class = $config['connection']['database']['creator_statement'];

        $class = isset($class) ? $class :  '\Miracuthbert\Multitenancy\Database\DatabaseCreatorStatement';

        return $class;
    }
}
