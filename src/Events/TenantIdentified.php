<?php

namespace Miracuthbert\Multitenancy\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Miracuthbert\Multitenancy\Models\Tenant;

class TenantIdentified
{
    use Dispatchable, SerializesModels;

    /**
     * Tenant instance.
     *
     * @var \Miracuthbert\Multitenancy\Models\Tenant $tenant
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
