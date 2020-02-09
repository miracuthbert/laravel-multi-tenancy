<?php

namespace Miracuthbert\Multitenancy\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static driver()
 * @method static bool driverHasDatabaseManager()
 * @method static bool driverUsesScope()
 * @method static \Miracuthbert\Multitenancy\Manager manager()
 * @method static \Miracuthbert\Multitenancy\Models\Tenant tenant()
 * @method static \Miracuthbert\Multitenancy\TenantConfigHelper config()
 * @method static \Miracuthbert\Multitenancy\TenantStore store()
 *
 * @see \Miracuthbert\Multitenancy\Tenancy
 */
class Tenancy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tenancy';
    }
}
