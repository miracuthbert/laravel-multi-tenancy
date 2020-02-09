<?php

namespace Miracuthbert\Multitenancy\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class TenancyModelMake extends ModelMakeCommand
{
    /**
     * Defines whether the model is for a multi-database driver.
     *
     * @var mixed
     */
    public $multiDatabase;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent based model class for tenant';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->setName('tenancy:model');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        $this->createUsersMigration();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $this->multiDatabase = $this->option('multi') !== false;

        if ($this->option('for-tenant') !== false) {
            return __DIR__ . '/stubs/for_tenant.stub';
        }

        if ($this->multiDatabase) {
            return __DIR__ . '/stubs/multi.model.stub';
        }

        return __DIR__ . '/stubs/model.stub';
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = $this->getTableName();

        $forTenants = ['--for-tenant' => false];

        if ($this->option('for-tenant') === false) {
            if ($this->option('columns-only') !== false) {
                $this->call('tenancy:migration', array_merge([
                    'name' => "add_tenant_columns_to_{$table}_table",
                    '--table' => $table,
                ], $forTenants));

                return;
            }

            $this->call('tenancy:migration', array_merge([
                'name' => "create_{$table}_table",
                '--create' => $table,
            ], $forTenants));

            return;
        }

        $this->call('tenancy:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--for-tenant' => true,
        ]);
    }

    /**
     * Creates a migration file for tenant users relation.
     *
     * @return void
     */
    protected function createUsersMigration()
    {
        if ($this->option('for-tenant') !== false) {
            return;
        }

        if ($this->option('no-users') !== false) {
            return;
        }

        $table = $this->getTableName(true) . '_user';

        $this->call('tenancy:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--for-tenant' => false,
            '--users' => true,
        ]);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge([
            ['for-tenant', null, InputOption::VALUE_OPTIONAL, 'Creates a migration that is related to the tenant', false],

            ['multi', null, InputOption::VALUE_OPTIONAL, 'Indicates if the generated file should be for a multi-database tenant', false],

            ['columns-only', null, InputOption::VALUE_OPTIONAL, 'Creates a migration that adds only tenant columns to an existing table', false],

            ['no-users', null, InputOption::VALUE_OPTIONAL, 'Indicates that a migration for tenant users relation should not be created', false],
        ], parent::getOptions());
    }

    /**
     * Get table name.
     *
     * @param bool $singular
     * @return string
     */
    protected function getTableName($singular = false)
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot') || $singular) {
            $table = Str::singular($table);
        }

        return $table;
    }
}
