<?php

namespace Miracuthbert\Multitenancy\Models;

interface Tenant
{
    /**
     * Get the users that are part of the tenant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();
}
