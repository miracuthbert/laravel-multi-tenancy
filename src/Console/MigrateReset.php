<?php

namespace Miracuthbert\Multitenancy\Console;

use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Migrations\Migrator;
use Miracuthbert\Multitenancy\Database\TenantDatabaseManager;
use Miracuthbert\Multitenancy\Traits\Console\AcceptsMultipleTenants;
use Miracuthbert\Multitenancy\Traits\Console\FetchesTenant;
use Miracuthbert\Multitenancy\Traits\Console\MigrationHelperTrait;

class MigrateReset extends ResetCommand
{
    use FetchesTenant, AcceptsMultipleTenants, MigrationHelperTrait;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback all database migrations for tenants';

    /**
     * Instance of tenant's Database Manager.
     *
     * @var TenantDatabaseManager
     */
    protected $db;

    /**
     * Create a new command instance.
     *
     * @param Migrator $migrator
     * @param TenantDatabaseManager $db
     */
    public function __construct(Migrator $migrator, TenantDatabaseManager $db)
    {
        parent::__construct($migrator);

        $this->setName('tenants:reset');

        $this->specifyParameters();

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
