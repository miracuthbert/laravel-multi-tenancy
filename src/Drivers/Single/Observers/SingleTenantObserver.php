<?php

namespace Miracuthbert\Multitenancy\Drivers\Single\Observers;

use Illuminate\Database\Eloquent\Model;
use Miracuthbert\Multitenancy\Drivers\TenantObserver;

class SingleTenantObserver extends TenantObserver
{
    /**
     * Instance of Tenant.
     *
     * @var Model
     */
    protected $tenant;

    /**
     * SingleTenantObserver constructor.
     *
     * @param $tenant
     * @return void
     */
    public function __construct(Model $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Handle the model "creating" event.
     *
     * @param Model $model
     * @return void
     */
    public function creating(Model $model)
    {
        $foreignKey = $this->tenant->getForeignKey();

        if (!isset($model->{$foreignKey})) {
            $model->setAttribute($foreignKey, $this->tenant->id);
        }
    }
}
