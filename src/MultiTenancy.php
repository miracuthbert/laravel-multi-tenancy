<?php

namespace Miracuthbert\Multitenancy;

use Illuminate\Database\Schema\Blueprint;

class MultiTenancy
{
    const TENANCY_CONFIG = 'tenancy-config';
    const TENANCY_ROUTES = 'tenancy-routes';
    const MULTI_DATABASE_MIGRATIONS = 'multi-database-migrations';

    const UUID = 'uuid';
    const DOMAIN = 'domain';

    /**
     * Get tenant table foreign key.
     *
     * @var string
     */
    public static $tenant_foreign_key;

    /**
     * Get tenant local key.
     *
     * @var string
     */
    public static $tenant_local_key;

    /**
     * Get tenant table.
     *
     * @var string
     */
    public static $tenant_table;

    /**
     * MultiTenancy constructor.
     *
     * @return void
     */
    public function __construct()
    {
        self::buildForTenantColumns();
    }

    /**
     * Add default tenant columns to the table.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @param array $options
     * @return void
     */
    public static function columns(Blueprint $table, $options = [])
    {
        $table->uuid(self::UUID);

        if ($options[self::DOMAIN] ?? true) {
            $table->string(self::DOMAIN)->unique()->index();
        }
    }

    /**
     * Drop default tenant columns from table.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return void
     */
    public static function dropColumns(Blueprint $table)
    {
        $table->dropColumn(self::getDefaultColumns());
    }

    /**
     * Add tenant relationship columns to a table regardless of driver.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return void
     */
    public static function tenantColumns(Blueprint $table)
    {
        self::buildForTenantColumns();

        $table->unsignedBigInteger(static::$tenant_foreign_key)->index();

        $table->foreign(static::$tenant_foreign_key)
            ->references(static::$tenant_local_key)
            ->on(static::$tenant_table)
            ->onDelete('cascade');
    }

    /**
     * Add default tenant columns to a tenant related table.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return void
     */
    public static function forTenantColumns(Blueprint $table)
    {
        if (static::driverUsesScope()) {
            self::buildForTenantColumns();

            $table->unsignedBigInteger(static::$tenant_foreign_key)->index();

            $table->foreign(static::$tenant_foreign_key)
                ->references(static::$tenant_local_key)
                ->on(static::$tenant_table)
                ->onDelete('cascade');
        }
    }

    /**
     * Drop default tenant columns from table.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return void
     */
    public static function dropForTenantColumns(Blueprint $table)
    {
        if (static::driverUsesScope()) {
            self::buildForTenantColumns();

            $table->dropForeign(self::getForTenantColumns());
            $table->dropColumn(self::getForTenantColumns());
        }
    }

    /**
     * Get default tenant columns.
     *
     * @return array
     */
    public static function getDefaultColumns()
    {
        return [self::UUID, self::DOMAIN];
    }

    /**
     * Determine if driver uses a database manager.
     *
     * @return mixed
     */
    public static function driverHasDatabaseManager()
    {
        return tenancy()->driverHasDatabaseManager();
    }

    /**
     * Determine if driver uses a tenant scope.
     *
     * @return mixed
     */
    public static function driverUsesScope()
    {
        return tenancy()->driverUsesScope();
    }

    /**
     * Gets for tenant columns.
     *
     * @return array
     */
    public static function getForTenantColumns()
    {
        self::buildForTenantColumns();

        return [static::$tenant_foreign_key];
    }

    /**
     * Setup for tenant columns.
     *
     * @return void
     */
    public static function buildForTenantColumns()
    {
        $model = tenancy()->tenantModel();

        static::$tenant_table = $model->getTable();

        static::$tenant_foreign_key = $model->getForeignKey();

        static::$tenant_local_key = 'id';
    }
}
