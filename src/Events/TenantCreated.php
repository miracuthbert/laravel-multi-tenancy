<?php

namespace Miracuthbert\Multitenancy\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Miracuthbert\Multitenancy\Models\Tenant;

class TenantCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Instance of Tenant.
     *
     * @var \Miracuthbert\Multitenancy\Models\Tenant
     */
    public $tenant;

    /**
     * Create a new event instance.
     *
     * @param \Miracuthbert\Multitenancy\Models\Tenant $tenant
     * @return void
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
