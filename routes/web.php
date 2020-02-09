<?php

/**
 * Tenant Switch Route
 */
Route::middleware(config('tenancy.redirect.middleware', []))
    ->get('/switch/{tenant}', 'TenantSwitchController')
    ->name('switch');
