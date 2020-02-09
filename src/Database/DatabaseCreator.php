<?php

namespace Miracuthbert\Multitenancy\Database;

use Illuminate\Support\Facades\DB;
use Miracuthbert\Multitenancy\Models\Tenant;

class DatabaseCreator
{
    /**
     * The database name.
     *
     * @var $database
     */
    public $database;

    /**
     * The database charset.
     *
     * @var $charset
     */
    public $charset;

    /**
     * The database collation.
     *
     * @var $collation
     */
    public $collation;

    /**
     * Create tenant's database.
     *
     * @param Tenant $tenant
     * @return bool
     */
    public function create(Tenant $tenant)
    {
        return DB::statement($this->getDatabaseCreateStatement($tenant));
    }

    /**
     * Get database create statement.
     *
     * @param Tenant $tenant
     * @return string
     */
    protected function getDatabaseCreateStatement(Tenant $tenant)
    {
        $database = $this->getTenantDatabaseName($tenant);
        $charset = $this->getDatabaseCharset();
        $collation = $this->getDatabaseCollation();

        return sprintf("CREATE DATABASE IF NOT EXISTS `%s`%s%s", $database, $charset, $collation);
    }

    /**
     * Get database name.
     *
     * @param Tenant $tenant
     * @return mixed
     */
    protected function getTenantDatabaseName(Tenant $tenant)
    {
        $this->database = config('tenancy.connection.database.prefix') . '_' . $tenant->id;

        return $this->database;
    }

    /**
     * Get database charset.
     *
     * @return mixed
     */
    protected function getDatabaseCharset()
    {
        $this->charset = '';

        $charset = config()->get($this->getConfigConnectionPath() . '.charset');

        if (config()->has('database.connections.' . $this->getDefaultConnectionName() . '.charset')) {
            return $this->charset = ' DEFAULT CHARACTER SET ' . $charset;
        }

        return $this->charset;
    }

    /**
     * Get database collation.
     *
     * @return mixed
     */
    protected function getDatabaseCollation()
    {
        $this->collation = '';

        $collation = config()->get($this->getConfigConnectionPath() . '.collation');

        if (config()->has('database.connections.' . $this->getDefaultConnectionName() . '.collation')) {
            $this->collation = ' DEFAULT COLLATE ' . $collation;
        }

        return $this->collation;
    }

    /**
     * Get database connection path.
     *
     * @return mixed
     */
    protected function getConfigConnectionPath()
    {
        return sprintf('database.connections.%s', $this->getDefaultConnectionName());
    }

    /**
     * Get default database connection name.
     *
     * @return mixed
     */
    protected function getDefaultConnectionName()
    {
        return config('database.default');
    }
}
