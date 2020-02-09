<?php

namespace Miracuthbert\Multitenancy\Drivers\Multi\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Miracuthbert\Multitenancy\Database\DatabaseCreator;
use Miracuthbert\Multitenancy\Events\TenantCreated;
use Miracuthbert\Multitenancy\Events\TenantDatabaseCreated;
use Miracuthbert\Multitenancy\TenancyDriver;

class CreateTenantDatabase
{
    /**
     * Instance of DatabaseCreator.
     *
     * @var \Miracuthbert\Multitenancy\Database\DatabaseCreator
     */
    protected $databaseCreator;

    /**
     * Create the event listener.
     *
     * @param \Miracuthbert\Multitenancy\Database\DatabaseCreator $databaseCreator
     * @return void
     */
    public function __construct(DatabaseCreator $databaseCreator)
    {
        $this->databaseCreator = $databaseCreator;
    }

    /**
     * Handle the event.
     *
     * @param  TenantCreated $event
     * @return void
     * @throws \Exception
     */
    public function handle(TenantCreated $event)
    {
        $tenant = $event->tenant;

        if ($this->getTenantDriver()->hasDatabaseManager()) {
            if (!$this->databaseCreator->create($tenant)) {
                throw  new \Exception("Database failed to be created.");
            }
            event(new TenantDatabaseCreated($tenant));
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
