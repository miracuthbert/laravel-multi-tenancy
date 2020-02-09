<?php

if (!function_exists('tenancy')) {
    /**
     * Get an instance of the Tenancy.
     *
     * @return mixed
     */
    function tenancy()
    {
        return app('tenancy');
    }
}

if (!function_exists('tenant')) {
    /**
     * Get an instance of the Tenant.
     *
     * @return mixed
     */
    function tenant()
    {
        return Tenancy::tenant();
    }
}
