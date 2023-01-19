<?php

namespace Miracuthbert\Multitenancy\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Miracuthbert\Multitenancy\TenantStore;

class TenantSwitchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param mixed $tenant
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $tenant)
    {
        $key = (tenancy()->config()->storeDriver() === 'db') ? config('tenancy.model.db_key') : config('tenancy.model.key');

        tenancy()->store()->putKey($tenant->{$key});

        return redirect(config('tenancy.redirect.url', '/'));
    }
}
