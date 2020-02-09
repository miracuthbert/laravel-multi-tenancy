<?php

namespace Miracuthbert\Multitenancy\Drivers\Multi;

use Miracuthbert\Multitenancy\Database\TenantDatabaseManager;
use Miracuthbert\Multitenancy\Drivers\DriverAbstract;
use Miracuthbert\Multitenancy\Drivers\TenantDriver;

class MultiDatabaseDriver extends DriverAbstract implements TenantDriver
{
    /**
     * The tenant observer.
     *
     * @return string
     */
    public function tenantObserver()
    {
        // TODO: Implement tenantObserver() method.
    }

    /**
     * Define whether the driver uses an observer.
     *
     * @return string
     */
    public function usesObserver()
    {
        return false;
    }

    /**
     * The tenant scope.
     *
     * @param $tenant
     * @return mixed
     */
    public function tenantScope($tenant)
    {
        // TODO: Implement tenantScope() method.
    }

    /**
     * Define whether the driver uses a scope.
     *
     * @return bool
     */
    public function usesScope()
    {
        return false;
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
        return true;
    }

    /**
     * The tenant's database manager.
     *
     * @return mixed
     */
    public function databaseManager()
    {
        return TenantDatabaseManager::class;
    }
}
