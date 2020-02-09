<?php

namespace Miracuthbert\Multitenancy\Traits\Console;

trait FetchesTenant
{
    /**
     * Get tenants within given ids.
     *
     * @param null $ids
     * @return $this|\Illuminate\Database\Eloquent\Builder
     */
    public function tenants($ids = null)
    {
        // fetch tenants
        $class = config('tenancy.model.class');

        $model = new $class;

        $tenants = $model->query();

        if ($ids) {
            $tenants = $tenants->whereIn('id', $ids);
        }

        return $tenants;
    }
}
