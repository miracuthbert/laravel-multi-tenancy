<?php

namespace Miracuthbert\Multitenancy\Drivers;

interface TenantDriver
{
    /**
     * The tenant observer.
     *
     * @return string
     */
    public function tenantObserver();

    /**
     * Define whether the driver uses an observer.
     *
     * @return string
     */
    public function usesObserver();

    /**
     * The tenant scope.
     *
     * @param $tenant
     * @return mixed
     */
    public function tenantScope($tenant);

    /**
     * Define whether the driver uses a scope.
     *
     * @return bool
     */
    public function usesScope();

    /**
     * The tenant cache manager.
     *
     * @return mixed
     */
    public function cacheManager();

    /**
     * Define whether the tenant uses a database manager.
     *
     * @return bool
     */
    public function hasDatabaseManager();

    /**
     * The tenant's database manager.
     *
     * @return mixed
     */
    public function databaseManager();
}
