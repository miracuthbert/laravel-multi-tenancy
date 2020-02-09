<?php

namespace Miracuthbert\Multitenancy\Drivers\Single\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Miracuthbert\Multitenancy\Events\TenantCreated;
use Miracuthbert\Multitenancy\Manager;

class RegisterCreatedTenant
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TenantCreated  $event
     * @return void
     */
    public function handle(TenantCreated $event)
    {
        app(Manager::class)->setTenant($event->tenant);
    }
}
