<?php

namespace Miracuthbert\Multitenancy;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class TenantStore
{
    const TENANT_HEADER = 'X-Tenancy-Tenant';

    /**
     * Get the tenant.
     *
     * @param null $default
     * @return mixed
     */
    public function getKey($default = null)
    {
        if ($value = $this->getKeyValue()) {
            return $value;
        }

        return $default;
    }

    /**
     * Store the tenant.
     *
     * @param $value
     * @return void
     */
    public function putKey($value)
    {
        $driver = tenancy()->config()->storeDriver();

        $key = $this->getStoreKeyIdentifier();

        switch ($driver) {
            case 'cache':
                Cache::put($key, $value, 3600);
                return;

            case 'cookie':
                Cookie::queue($key, $value, 60);
                return;

            case 'db':
                if (auth()->user()->{$key} !== $value) {
                    auth()->user()->update([
                        $key => $value // todo: set to match config key
                    ]);
                }
                return;
    
            default:
                session()->put($key, $value);
                return;
        }
    }

    /**
     * Get the value from the key.
     *
     * @return mixed
     */
    private function getKeyValue()
    {
        $key = $this->getStoreKeyIdentifier();

        $driver = tenancy()->config()->storeDriver();

        switch ($driver) {
            case 'cache':
                return Cache::get($key);

            case 'cookie':
                return Cookie::get($key);

            case 'db':
                return auth()->user()->{$key};
    
            default:
                return session()->get($key);
        }
    }

    /**
     * Get the unique store key identifier.
     *
     * @return string
     */
    public function getStoreKeyIdentifier()
    {
        $prefix = tenancy()->config()->storeKey();

        if (tenancy()->config()->storeDriver() === 'db') {
            return tenancy()->config()->getOption('store.db_key');
        }

        return $prefix . '_' . Str::slug(request()->ip(), '_');
    }
}
