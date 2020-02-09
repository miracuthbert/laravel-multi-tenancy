<?php

namespace Miracuthbert\Multitenancy;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Miracuthbert\Multitenancy\Drivers\Multi\Listeners\CreateTenantDatabase;
use Miracuthbert\Multitenancy\Drivers\Multi\Listeners\SetUpTenantDatabase;
use Miracuthbert\Multitenancy\Events\TenantCreated;
use Miracuthbert\Multitenancy\Events\TenantDatabaseCreated;
use Miracuthbert\Multitenancy\Events\TenantIdentified;
use Miracuthbert\Multitenancy\Listeners\RegisterTenant;
use Miracuthbert\Multitenancy\Listeners\UseTenantFileSystem;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        TenantCreated::class => [
            CreateTenantDatabase::class,
        ],
        TenantIdentified::class => [
            RegisterTenant::class,
            UseTenantFileSystem::class,
        ],
        TenantDatabaseCreated::class => [
            SetUpTenantDatabase::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
