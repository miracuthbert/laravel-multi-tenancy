<?php

namespace Miracuthbert\Multitenancy\Traits;

use Miracuthbert\Multitenancy\TenancyDriver;

trait TenancyDriverTrait
{
    /**
     * Get the tenancy driver.
     *
     * @return mixed
     */
    public function driver()
    {
        $driver = app(TenancyDriver::class);

        return $driver->getDriver();
    }

    /**
     * Determine if driver uses a database manager.
     *
     * @return bool
     */
    public function driverHasDatabaseManager()
    {
        return $this->driver()->hasDatabaseManager();
    }

    /**
     * Determine if driver uses a tenant scope.
     *
     * @return bool
     */
    public function driverUsesScope()
    {
        return $this->driver()->usesScope();
    }
}
