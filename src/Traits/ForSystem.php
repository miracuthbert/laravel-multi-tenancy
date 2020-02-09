<?php

namespace Miracuthbert\Multitenancy\Traits;

trait ForSystem
{
    /**
     * Get the current connection name to be used by the systems.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return env('DB_CONNECTION');
    }
}
