<?php

namespace Miracuthbert\Multitenancy\Drivers\Multi\Listeners;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Miracuthbert\Multitenancy\Events\TenantDatabaseCreated;
use Miracuthbert\Multitenancy\Models\Tenant;

class SetUpTenantDatabase
{
    /**
     * Handle the event.
     *
     * @param  TenantDatabaseCreated  $event
     * @return void
     */
    public function handle(TenantDatabaseCreated $event)
    {
        if ($this->migrate($event->tenant)) {
            $this->seed($event->tenant);
        }
    }

    /**
     * Migrate tenant's database.
     *
     * @param Tenant $tenant
     * @return bool
     */
    protected function migrate(Tenant $tenant)
    {
        $migration = Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id]
        ]);

        return $migration === 0;
    }

    /**
     * Seed tenant's database.
     *
     * @param Tenant $tenant
     * @return int
     */
    protected function seed(Tenant $tenant)
    {
        return Artisan::call('tenants:seed', [
            '--tenants' => [$tenant->id]
        ]);
    }
}
