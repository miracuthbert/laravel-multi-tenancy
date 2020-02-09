<?php

namespace Miracuthbert\Multitenancy\Drivers;

use Illuminate\Database\Eloquent\Model;

class TenantObserver
{
    /**
     * TenantObserver constructor.
     *
     * @param Model $tenant
     * @return void
     */
    public function __construct(Model $tenant)
    {
        //
    }
}
