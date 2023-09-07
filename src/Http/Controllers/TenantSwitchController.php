<?php

namespace Miracuthbert\Multitenancy\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;

class TenantSwitchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  mixed  $tenant
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $tenant)
    {
        $key = (tenancy()->config()->storeDriver() === 'db') ? config('tenancy.model.db_key') : config('tenancy.model.key');

        tenancy()->store()->putKey($tenant->{$key});

        $config = tenancy()->config();

        if ($config->getOption('routes.subdomain', false) && $config->getOption('redirect.route')) {
            $key = tenancy()->config()->getOption('routes.subdomain_key', 'domain');

            return redirect()->route(
                $request->redirect_to ?? config('tenancy.redirect.route'),
                array_merge([$tenant->{$key}], Arr::wrap($request->redirect_params))
            );
        }

        return redirect(config('tenancy.redirect.url', '/'));
    }
}
