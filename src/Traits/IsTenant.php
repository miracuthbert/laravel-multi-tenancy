<?php

namespace Miracuthbert\Multitenancy\Traits;

use Miracuthbert\Multitenancy\Models\Tenant;
use Miracuthbert\Multitenancy\Models\TenantConnection;
use Miracuthbert\Multitenancy\TenancyDriver;
use Webpatser\Uuid\Uuid;

trait IsTenant
{
    /**
     * The `booting` method of the trait.
     *
     * @return void
     */
    public static function bootIsTenant()
    {
        static::creating(function ($tenant) {
            $tenant->uuid = Uuid::generate(4);
        });

        static::created(function ($tenant) {
            // check if driver is multi-database and create a tenant database connection
            if (tenancy()->driverHasDatabaseManager()) {
                $tenant->tenantConnection()->save(
                    static::newDatabaseConnection($tenant)
                );
            }

            tenancy()->driver()->fireTenantCreatedEvent($tenant);
        });
    }

    /**
     * Get the tenant driver.
     *
     * @return mixed
     */
    public function getTenantDriver()
    {
        $driver = app(TenancyDriver::class);

        return $driver->getDriver();
    }

    /**
     * Setup new tenant database connection.
     *
     * @param \Miracuthbert\Multitenancy\Models\Tenant $tenant
     * @return TenantConnection
     */
    protected static function newDatabaseConnection(Tenant $tenant)
    {
        return new TenantConnection([
            'database' => config('tenancy.connection.database.prefix') . '_' . $tenant->id,
        ]);
    }

    /**
     * Get company's database connection.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tenantConnection()
    {
        $model = tenancy()->tenantModel();

        return $this->hasOne(TenantConnection::class, $model->getForeignKey(), 'id');
    }

    /**
     * Get the users that are part of the tenant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        $table = config('tenancy.users.tenant_user');

        return $this->belongsToMany(
            get_class(tenancy()->tenantUserModel()),
            isset($table) ? $table : null
        )->withTimestamps();
    }
}
