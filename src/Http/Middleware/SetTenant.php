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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $paramKey = tenancy()->config()->getOption('model.route_key');

        $options = tenancy()->config()->getOption('routes.middleware.set_tenant');

        list('in_tenant' => $inTenant, 'in_header' => $inHeader) = $options;

        $value = $inHeader ? $request->header(TenantStore::TENANT_HEADER) : tenancy()->store()->getKey($request->{$paramKey});

        $tenant = tenancy()->resolveTenant($value);

        if (!$tenant) {
            return config('tenancy.redirect.abort') ? abort(404) : redirect(config('tenancy.redirect.fallback_url'));
        }

        if ($this->canAccessTenant($request, $inTenant, $tenant)) {
            return config('tenancy.redirect.abort') ? abort(404) : redirect(config('tenancy.redirect.fallback_url'));
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
    protected function canAccessTenant($request, $inTenant, $tenant)
    {
        return $inTenant && !$tenant->users->contains($request->user());
    }
}
