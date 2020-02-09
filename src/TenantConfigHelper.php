<?php

namespace Miracuthbert\Multitenancy;

use Illuminate\Support\Arr;

class TenantConfigHelper
{
    /**
     * Get the tenant store driver.
     *
     * @return string
     */
    public function storeDriver()
    {
        return Arr::get($this->getStore(), 'driver');
    }

    /**
     * Get the tenant store key name.
     *
     * @return string
     */
    public function storeKey()
    {
        return Arr::get($this->getStore(), 'key');
    }

    /**
     * Get tenant store.
     *
     * @return mixed
     */
    public function getStore()
    {
        return Arr::get($this->options(), 'store');
    }

    /**
     * Get an option's value from config.
     *
     * @param $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return Arr::get($this->options(), $key, $default);
    }

    /**
     * Get the package config.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function options()
    {
        return config('tenancy');
    }
}
