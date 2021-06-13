<?php

namespace Miracuthbert\Multitenancy\Drivers;

use Illuminate\Contracts\Events\Dispatcher;
use Miracuthbert\Multitenancy\Events\TenantCreated;

abstract class DriverAbstract
{
    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function setDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Fire the tenant created event event if the dispatcher is set.
     *
     * @param  \Miracuthbert\Multitenancy\Models\Tenant $tenant
     * @return void
     */
    public function fireTenantCreatedEvent($tenant)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new TenantCreated($tenant));
        }
    }
}
