<?php

namespace Miracuthbert\Multitenancy\Drivers\Single;

use Miracuthbert\Multitenancy\Drivers\DriverAbstract;
use Miracuthbert\Multitenancy\Drivers\Single\Observers\SingleTenantObserver;
use Miracuthbert\Multitenancy\Drivers\Single\Scopes\SingleTenantScope;
use Miracuthbert\Multitenancy\Drivers\TenantDriver;

class SingleDatabaseDriver extends DriverAbstract implements TenantDriver
{
    /**
     * The tenant observer
     *
     * @return string
     */
    public function tenantObserver()
    {
        return SingleTenantObserver::class;
    }

    /**
     * Define whether the driver uses an observer.
     *
     * @return string
     */
    public function usesObserver()
    {
        return true;
    }

    /**
     * The tenant scope.
     *
     * @param $tenant
     * @return mixed
     */
    public function tenantScope($tenant)
    {
        if (self::usesScope($tenant)) {
            return new SingleTenantScope($tenant);
        }

        return null;
    }

    /**
     * Define whether the driver uses a scope.
     *
     * @return bool
     */
    public function usesScope()
    {
        return true;
    }

    /**
     * The tenant cache manager.
     *
     * @return mixed
     */
    public function cacheManager()
    {
        // TODO: Implement cacheManager() method.
    }

    /**
     * Define whether the tenant uses a database manager.
     *
     * @return bool
     */
    public function hasDatabaseManager()
    {
        return false;
    }

    /**
     * The tenant's database manager.
     *
     * @return mixed
     */
    public function databaseManager()
    {
        // TODO: Implement databaseManager() method.
    }
}
