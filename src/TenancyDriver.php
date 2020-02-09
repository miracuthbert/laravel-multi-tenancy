<?php

namespace Miracuthbert\Multitenancy;

use Miracuthbert\Multitenancy\Drivers\TenantDriver;

class TenancyDriver
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * TenancyDriver constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get the tenancy driver.
     *
     * @return TenantDriver
     */
    public function getDriver()
    {
        $config = config('tenancy');

        $default = $config['default'];

        $driver = $config['drivers'][$default]['handler'] ?? 'single';

        $driver = new $driver;

        if (method_exists($driver, 'setDispatcher')) {
            $driver->setDispatcher($this->app['events']);
        }

        return $driver;
    }
}
