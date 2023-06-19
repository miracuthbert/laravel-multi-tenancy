<?php

namespace Miracuthbert\Multitenancy\Traits;

use Illuminate\Database\Eloquent\Builder;
use Miracuthbert\Multitenancy\Models\Tenant;

trait ForTenants
{
    /**
     * The `booting` method of the trait.
     *
     * @return void
     */
    public static function bootForTenants()
    {
        if (self::disableTenantUsage()) {
            return;
        }

        $driver = tenancy()->driver();

        $manager = tenancy()->manager();

        if ($manager->hasTenant()) {
            if ($driver->usesScope()) {
                static::addGlobalScope(
                    $driver->tenantScope($manager->getTenant())
                );
            }

            if ($driver->usesObserver()) {
                $observer = $driver->tenantObserver();

                if ($observer !== null) {
                    static::observe(
                        $observer
                    );
                }
            }
        }
    }

    /**
     * Disable model usage by tenant.
     *
     * @return bool
     */
    public static function disableTenantUsage()
    {
        return false;
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName()
    {
        $driver = tenancy()->driver();

        $manager = tenancy()->manager();

        if ($manager->hasTenant() && $driver->hasDatabaseManager()) {
            return 'tenant';
        }

        return parent::getConnectionName();
    }

    /**
     * Scope a query to exclude 'tenant' scope.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithoutForTenants(Builder $builder)
    {
        if (!tenancy()->driver()->usesScope()) {
            return $builder;
        }

        return $builder->withoutGlobalScope(
            get_class(
                tenancy()->driver()->tenantScope(Tenant::class)
            )
        );
    }
}
