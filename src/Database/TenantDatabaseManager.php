<?php

namespace Miracuthbert\Multitenancy\Database;

use Illuminate\Database\DatabaseManager;
use Miracuthbert\Multitenancy\Models\Tenant;

class TenantDatabaseManager
{
    /**
     * Database Manager Instance.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $db;

    /**
     * TenantDatabaseManager constructor.
     *
     * @param \Illuminate\Database\DatabaseManager $db
     * @return void
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Create tenant's connection.
     *
     * @param Tenant $tenant
     */
    public function createConnection(Tenant $tenant)
    {
        config()->set('database.connections.tenant', $this->getTenantConnection($tenant));
    }

    /**
     * Reconnect the tenant's database.
     *
     * @return void
     */
    public function connectToTenant()
    {
        $this->db->reconnect('tenant');
    }

    /**
     * Disconnect from the given database and remove from local cache.
     *
     * @return void
     */
    public function purge()
    {
        $this->db->purge('tenant');
    }

    /**
     * Get tenant's connection.
     *
     * @param Tenant $tenant
     * @return array
     */
    protected function getTenantConnection(Tenant $tenant)
    {
        return array_merge(
            config()->get($this->getConfigConnectionPath()),
            $tenant->tenantConnection->only('database')
        );
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
