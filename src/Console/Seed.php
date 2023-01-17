<?php

namespace Miracuthbert\Multitenancy\Console;

use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Miracuthbert\Multitenancy\Database\TenantDatabaseManager;
use Miracuthbert\Multitenancy\Traits\Console\AcceptsMultipleTenants;
use Miracuthbert\Multitenancy\Traits\Console\FetchesTenant;

class Seed extends SeedCommand
{
    use FetchesTenant, AcceptsMultipleTenants;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds tenant databases';

    /**
     * Instance of tenant's Database Manager.
     *
     * @var TenantDatabaseManager
     */
    protected $db;

    /**
     * Create a new command instance.
     *
     * @param Resolver $resolver
     * @param TenantDatabaseManager $db
     */
    public function __construct(Resolver $resolver, TenantDatabaseManager $db)
    {
        parent::__construct($resolver);

        $this->setName('tenants:seed');

        $this->db = $db;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        // set the migration database to tenant's
        $this->input->setOption('database', 'tenant');

        // get tenants
        $ids = $this->option('tenants');

        // loop through tenants and run their migrations
        $this->tenants($ids)->each(function ($tenant) {

            // create tenant db connection
            $this->db->createConnection($tenant);

            // connect to the tenant
            $this->db->connectToTenant();

            parent::handle();

            // purge tenant's connection
            $this->db->purge();
        });
    }
}
