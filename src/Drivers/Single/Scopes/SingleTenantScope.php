<?php

namespace Miracuthbert\Multitenancy\Drivers\Single\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SingleTenantScope implements Scope
{
    /**
     * Instance of Tenant model.
     *
     * @var Model
     */
    protected $tenant;

    /**
     * SingleTenantScope constructor.
     *
     * @param  $tenant
     * @return void
     */
    public function __construct(Model $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Apply the scope to query only the matching tenant's records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where($this->tenant->getForeignKey(), '=', $this->tenant->id);
    }
}
