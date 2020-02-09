<?php

namespace Miracuthbert\Multitenancy;

use Miracuthbert\Multitenancy\Models\Tenant;

class Manager
{
    /**
     * Instance of Tenant.
     *
     * @var \Miracuthbert\Multitenancy\Models\Tenant
     */
    protected $tenant;

    /**
     * Get tenant.
     *
     * @return mixed
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Set tenant.
     *
     * @param \Miracuthbert\Multitenancy\Models\Tenant $tenant
     */
    public function setTenant(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Check if tenant exists in request.
     *
     * @return bool
     */
    public function hasTenant()
    {
        return isset($this->tenant);
    }
}
