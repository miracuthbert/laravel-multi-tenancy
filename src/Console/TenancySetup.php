<?php

namespace Miracuthbert\Multitenancy\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Miracuthbert\Multitenancy\MultiTenancy;
use Symfony\Component\Console\Input\InputArgument;

class TenancySetup extends Command
{
    use ConfirmableTrait, PackageSetupTrait;

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs and does the basic setup needed for multi-tenancy';

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->files = $filesystem;
        $this->setName('tenancy:setup');
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

        // check if setup is for a multi-database driver
        $multiDatabase = $this->option('multi') !== false;

        // get model
        $model = $this->option('model');

        $provider = "Miracuthbert\Multitenancy\LaravelMultiTenancyServiceProvider";

        $this->info('Installing Multitenancy package...');

        $this->info('Publishing configuration...');

        // publish config
        $this->call('vendor:publish', [
            '--provider' => $provider,
            '--tag' => 'tenancy-config',
        ]);

        $this->info('Publishing tenant routes file...');

        // publish routes
        $this->call('vendor:publish', [
            '--provider' => $provider,
            '--tag' => 'tenancy-routes',
        ]);

        $columnsOnly = $this->option('columns-only') !== false;

        // setup tenant model
        if ($model !== null) {
            $this->setupTenantModel($model, $multiDatabase, $columnsOnly);
        } else if ($model === null && ($table = $this->option('table')) !== null) {
             $this->setupTenantColumnsOnly($table);
        }

        // publish migrations
        if ($multiDatabase) {
            // generate seeder
            $this->call('make:seeder', [
                'name' => 'TenantDatabaseSeeder',
            ]);

            if (!class_exists('CreateTenantConnectionsTable') && !$this->multiDatabaseMigrationsAlreadyPublished()) {
                $this->call('vendor:publish', [
                    '--provider' => $provider,
                    '--tag' => MultiTenancy::MULTI_DATABASE_MIGRATIONS,
                ]);
            } else {
                $this->error('Tenant connections table already exists!');
            }
        }

        $this->showThanks();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['driver', InputArgument::OPTIONAL, 'The tenant driver to use', config('tenancy.default')],
        ];
    }

    /**
     * Determine if multi-database migrations already published.
     *
     * @return bool
     */
    protected function multiDatabaseMigrationsAlreadyPublished()
    {
        return Collection::make($this->getLaravel()->databasePath('migrations' . DIRECTORY_SEPARATOR))
            ->flatMap(function ($path) {
                return $this->files->glob($path . '*_create_tenant_connections_table.php');
            })
            ->count() > 0;
    }
}
