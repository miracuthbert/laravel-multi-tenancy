<?php

use Illuminate\Support\Str;

return [

    /*
     *
     * The tenancy driver to use in the app.
     *
     * Supported drivers: single, multi
     *
     */
    'default' => 'single',

    /*
     *
     * The tenant's model options to use in the app.
     *
     */
    'model' => [

        /*
         *
         * The tenant model.
         *
         */
        'class' => '',

        /*
         *
         * The column to query against in the tenant model.
         *
         */
        'key' => 'uuid',

        /*
         *
         * The column to query against in the tenant model when using "DB" store.
         *
         */
        'db_key' => 'id',

        /*
         *
         * The route key used by the model.
         *
         * Used to fetch the tenant's unique identifier directly from the request.
         * eg. 'slug' => $request->slug, 'team' => $request->team
         *
         * Note: When not using the tenant store functionality, make sure this
         * value matches the key option above.
         *
         */
        'route_key' => 'id',
    ],

    /*
     *
     * Configure the User to use for tenant.
     *
     */
    'users' => [

        /*
         *
         * The User model to use.
         *
         */
        'model' => \App\User::class,

        /*
         *
         * The name of the tenant's user table to use.
         *
         * Leave blank unless you don't follow Laravel's pivot table naming convention.
         *
         */
        'tenant_user' => null,
    ],

    /*
     *
     * The tenant's connection settings.
     *
     */
    'connection' => [

        /*
         *
         * The database connection config.
         *
         */

        'database' => [

            /*
             *
             * The prefix for the tenant's database name.
             *
             * Note: It should not be changed once set to maintain consistency throughout the app.
             *
             */

            'prefix' => Str::slug(env('APP_NAME', 'laravel'), '_'),

            /*
             *
             * The class that should be used to resolve database create statements based on connection.
             *
             * The default class handles: "mysql" and "pgsql".
             *
             */

            'creator_statement' => \Miracuthbert\Multitenancy\Database\DatabaseCreatorStatement::class,
        ],

    ],

    /*
     *
     * The routes options is used to define the tenant routing.
     *
     * Use this option if you are using the controller provided by the package
     * to switch between tenants.
     *
     */
    'routes' => [

        /*
         *
         * Set whether to use subdomains.
         *
         * The package supports subdomain routing and handles all the setup
         * that is required within the package.
         *
         * Note: You should make sure your server is enabled to use wildcard
         * subdomains.
         *
         * When enabled you should set the 'route' value to be used for
         * redirection in the 'redirect' section below.
         *
         */
        'subdomain' => false,

        /*
         *
         * The slug or value that package will use to identify subdomain from url.
         *
         * It will also be used to assign the default parameter for routes in the
         * subdomain group.
         *
         * Note: Unless this key conflicts with another, don't change.
         *
         */
        'subdomain_request_key' => "tenant_domain",

        /*
         *
         * The slug or value corresponding to a field in the tenant model.
         *
         * Note: if using default setup, it is the "domain" field.
         *
         */
        'subdomain_key' => "domain",

        /*
         *
         * The routes that use 'tenant' middleware but are under a
         * non-tenant subdomain.
         *
         * Example: 'cms.*', 'crm.*', 'blog.*'
         *
         * Tenant will be resolved from store rather than subdomain.
         *
         * Note: Make sure the user knows which tenant account they are
         * using.
         *
         */
        'subdomain_safe_routes' => [
            // 'projects.*',
        ],

        /*
         *
         * The tenant routes prefix.
         *
         */
        'prefix' => '/',

        /*
         *
         * The prefix to the tenant route names.
         *
         * eg. 'tenant.'
         *
         */
        'as' => '',

        /*
         *
         * The namespace for your tenant controllers.
         *
         */
        'namespace' => 'App\Http\Controllers\Tenant',

        /*
         *
         * The middleware to protect the tenant routes.
         *
         */
        'middleware' => [

            /*
             *
             * The middleware to apply before the tenant middleware.
             *
             */
            'before' => [
                'web',
                'auth',
            ],

            /*
             *
             * The options to pass to the tenant middleware.
             *
             */
            'set_tenant' => [

                /*
                 *
                 * Determines whether the current user is authorized to access tenant.
                 *
                 */
                'in_tenant' => false,

                /*
                 *
                 * Determines whether the current tenant should be fetched from the request header.
                 *
                 */
                'in_header' => false,
            ],

            /*
             *
             * The middleware to add to the tenant group middleware.
             *
             */
            'tenant' => [

            ],

            /*
             *
             * The middleware to apply after the tenant middleware group.
             *
             */
            'after' => [
                // tenant_config
                // theme_config
            ],

            /*
             *
             * Custom tenant middleware to use according to your requirements app wide.
             *
             * Use \Miracuthbert\Multitenancy\Http\Middleware\SetTenant::class as reference
             * when defining custom middleware to suit your needs.
             *
             * They provide the same function as 'tenant' middleware group (SetTenant::ckass)
             * with exception to checking whether the request user can access tenant.
             * 
             * Suitable for use in 'guest' routes when you need to display 'tenant' data
             * for public; eg. blogs, cms, etc
             *
             * Note: 'tenant' middleware group will have top priority compared to values
             * registered below. Do not use them on the same routes.
             */
            'groups' => [
                'tenant.guest' => [
                    \Miracuthbert\Multitenancy\Http\Middleware\ResolveTenantAsGuest::class,
                ],
            ],
        ],

        /*
         *
         * The path to the tenant's routes file.
         *
         */
        'file' => base_path('routes/tenant.php'),
    ],

    /*
     *
     * The redirect options used on switching a tenant.
     *
     * Use this option if you are using the controller provided by the package
     * to switch between tenants.
     *
     */
    'redirect' => [

        /*
         *
         * The url to redirect to by default when a tenant is switched.
         *
         */
        'url' => '/',

        /*
         *
         * The route name to redirect used when a tenant is switched.
         * 
         * Applied when using subdomain.
         * 
         * eg. 'tenant.home', 'tenant.dashboard'
         *
         * Note: This route should exist in your 'routes/tenant.php'
         * file.
         *
         */
        'route' => '',

        /*
         *
         * Set whether to abort if tenant not found or user is not authorized to
         * access a tenant.
         *
         */
        'abort' => false,

        /*
         *
         * The url to redirect back to if tenant not found.
         *
         */
        'fallback_url' => '/',

        /*
         *
         * The middleware to protect the redirect url.
         *
         */
        'middleware' => [
            'web',
           // 'auth'
        ],
    ],

    /*
     *
     * This controls the key and driver to use when switching or identifying
     * the tenant to be loaded.
     *
     * Supported drivers: "session", "cache", "cookie", "db"
     *
     */
    'store' => [

        /*
         *
         * The unique key used for storing tenant in cache or session.
         *
         */
        'key' => 'tenant',

        /*
         *
         * The unique column used for storing tenant in "DB".
         *
         */
        'db_key' => 'current_team_id',

        /*
         *
         * Set whether to use Session, Cache, Cookie or Database to keep track of active tenant.
         *
         */
        'driver' => 'session',
    ],

    /*
     *
     * This controls the console options to be used by tenant.
     *
     */
    'console' => [

        /*
         *
         * The commands related to tenant.
         *
         */
        'commands' => [

            /*
             *
             * The migrator commands used by the tenant.
             *
             * Add commands that handle tenant migration's here.
             *
             * Note: The commands must be implementing the MigrationRepositoryInterface.
             *
             */
            'migrator' => [],

            /*
             *
             * The db commands used by the tenant.
             *
             * Add commands that connect with a tenant's database here.
             *
             * Note: The commands must be implementing the ConnectionResolverInterface.
             *
             */
            'db' => [],

        ],
    ],

    /*
     *
     * Here are all the tenant drivers available for your application.
     *
     * You can use the drivers below as samples to create your own driver.
     *
     */
    'drivers' => [

        'single' => [
            'handler' => Miracuthbert\Multitenancy\Drivers\Single\SingleDatabaseDriver::class,
        ],

        'multi' => [
            'handler' => Miracuthbert\Multitenancy\Drivers\Multi\MultiDatabaseDriver::class,
        ],

    ],

];
