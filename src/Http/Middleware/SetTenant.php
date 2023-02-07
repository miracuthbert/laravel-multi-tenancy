<?php

namespace Miracuthbert\Multitenancy\Http\Middleware;

use Closure;
use Miracuthbert\Multitenancy\Events\TenantIdentified;
use Miracuthbert\Multitenancy\TenantStore;

class SetTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $subdomainKey = config('tenancy.routes.subdomain_request_key', 'tenant_domain');

        // set default subdomain key if it exists
        \Illuminate\Support\Facades\URL::defaults([$subdomainKey => $request->{$subdomainKey}]);

        $paramKey = tenancy()->config()->getOption('model.route_key');

        $options = tenancy()->config()->getOption('routes.middleware.set_tenant');

        list('in_tenant' => $inTenant, 'in_header' => $inHeader) = $options;

        if (tenancy()->config()->getOption('routes.subdomain')) {
            $value = ($request->{$subdomainKey});
        } else {
            $value = $inHeader ? $request->header(TenantStore::TENANT_HEADER) : tenancy()->store()->getKey($request->{$paramKey});
        }

        $tenant = tenancy()->resolveTenant($value);

        if (!$tenant) {
            return config('tenancy.redirect.abort') ? abort(404) : redirect(config('tenancy.redirect.fallback_url'));
        }

        if ($guard !== 'guest') {
            if ($this->cannotAccessTenant($request, $inTenant, $tenant)) {
                return config('tenancy.redirect.abort') ? abort(404) : redirect(config('tenancy.redirect.fallback_url'));
            }
        }

        event(new TenantIdentified($tenant));

        $tenantKey = tenancy()->config()->getOption('model.key');

        $request->headers->set(TenantStore::TENANT_HEADER, $tenant->{$tenantKey});

        return $next($request);
    }

    /**
     * Check if current user is authorized to access tenant.
     *
     * @param $request
     * @param $inTenant
     * @param $tenant
     * @return bool
     */
    protected function cannotAccessTenant($request, $inTenant, $tenant)
    {
        return $inTenant && !$tenant->users->contains($request->user());
    }
}
