<?php

namespace Miracuthbert\Multitenancy\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Miracuthbert\Multitenancy\Tests\TestCase;

class SetupMultitenancyPackageTest extends TestCase
{
    /**
     * @test
     */
    public function tenancy_setup_command_copies_the_required_files()
    {
        if (File::exists($configFile = config_path('tenancy.php'))) {
            unlink($configFile);
        }

        $this->assertFalse(File::exists($configFile));

        if (File::exists($tenantRoutesFile = config('tenancy.routes.file'))) {
            unlink($tenantRoutesFile);
        }

        $this->assertFalse(File::exists($tenantRoutesFile));

        Artisan::call('tenancy:setup');

        $this->assertTrue(File::exists($configFile));

        $this->assertTrue(File::exists($tenantRoutesFile));
    }

    /**
     * @test
     */
    public function tenancy_setup_command_copies_the_multi_driver_required_files()
    {
        $tenant_connection_path = database_path('migrations/*_create_tenant_connections_table.php');
        $tenantMigrationsFolder = database_path('migrations/tenant/');
        $seederFile = database_path('seeds/TenantDatabaseSeeder.php');

        // run setup multi-database setup
        Artisan::call('tenancy:setup', [
            '--multi' => true
        ]);

        $this->assertTrue(File::exists($seederFile));

        $this->assertTrue(File::exists($tenantMigrationsFolder));

        $this->assertTrue(count(File::glob($tenant_connection_path)) > 0);

        if (File::exists($seederFile)) {
            unlink($seederFile);
        }

        if (File::exists($tenantMigrationsFolder)) {
            File::deleteDirectory($tenantMigrationsFolder);
        }

        // remove multi-database migrations
        foreach (File::glob($tenant_connection_path) as $path) {
            unlink($path);
        }
    }
}
