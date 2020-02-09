<?php

namespace Miracuthbert\Multitenancy\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Miracuthbert\Multitenancy\Models\Tenant;

class TenantDatabaseCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Tenant instance.
     *
     * @var Tenant
     */
    public $tenant;

    /**
     * Create a new event instance.
     *
     * @param Tenant $tenant
     * @return void
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
