<?php

use Illuminate\Support\Facades\Route;

$groupOptions = [
    'prefix' => '/tenant',
    'namespace' => 'Miracuthbert\Multitenancy\Http\Controllers',
    'as' => 'tenant.',
];

if (tenancy()->config()->getOption('routes.subdomain', false)) {
    $groupOptions = array_merge($groupOptions, ['domain' => env('APP_URL')]);
}

Route::group($groupOptions, function () {
    /**
     * Tenant Switch Route
     */
    Route::middleware(config('tenancy.redirect.middleware', []))
        ->get('/switch/{tenant}', 'TenantSwitchController')
        ->name('switch');
});
