<?php

namespace Miracuthbert\Multitenancy\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Miracuthbert\Multitenancy\Database\TenantDatabaseManager;
use Miracuthbert\Multitenancy\Events\TenantIdentified;
use Miracuthbert\Multitenancy\Manager;
use Miracuthbert\Multitenancy\TenancyDriver;

class RegisterTenant
{
    /**
     * Instance of tenant's Database Manager.
     *
     * @var TenantDatabaseManager
     */
    protected $db;

    /**
     * Create the event listener.
     *
     * @param TenantDatabaseManager $db
     * @return void
     */
    public function __construct(TenantDatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Handle the event.
     *
     * @param  TenantIdentified  $event
     * @return void
     */
    public function handle(TenantIdentified $event)
    {
        app(Manager::class)->setTenant($event->tenant);

        if ($this->getTenantDriver()->hasDatabaseManager()) {
            $this->db->createConnection($event->tenant);
        }
    }

    /**
     * Get tenant driver.
     *
     * @return mixed
     */
    public function getTenantDriver()
    {
        $driver = app(TenancyDriver::class);

        return $driver->getDriver();
    }
}
