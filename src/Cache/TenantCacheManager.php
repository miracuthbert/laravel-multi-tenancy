<?php

namespace Miracuthbert\Multitenancy\Cache;

use Illuminate\Cache\CacheManager;
use Miracuthbert\Multitenancy\Manager;

class TenantCacheManager extends CacheManager
{
    /**
     * Dynamically call the default driver instance.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ($this->getTenantManager()->hasTenant()) {
            if ($method === 'tags') {
                return $this->store()->tags(
                    array_merge($this->getTenantCacheTag(), ...$parameters)
                );
            }
            return $this->store()->tags($this->getTenantCacheTag())->$method(...$parameters);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Get tenant's cache tag.
     *
     * @return array
     */
    protected function getTenantCacheTag()
    {
        return ['tenant_' . $this->getTenantManager()->getTenant()->uuid];
    }

    /**
     * Get tenant.
     *
     * @return mixed
     */
    protected function getTenantManager()
    {
        return $this->app[Manager::class];
    }
}
