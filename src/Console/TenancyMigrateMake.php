<?php

namespace Miracuthbert\Multitenancy\Console;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;
use Miracuthbert\Multitenancy\Traits\TenancyDriverTrait;
use Symfony\Component\Console\Input\InputOption;

class TenancyMigrateMake extends MigrateMakeCommand
{
    use TenancyDriverTrait;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file for tenant';

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Database\Migrations\MigrationCreator $creator
     * @param  \Illuminate\Support\Composer $composer
     * @return void
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct($creator, $composer);

        $this->setName('tenancy:migration');

        $collection = $this->addMoreOptions();

        $this->getDefinition()->addOptions($collection->all());
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('users') !== false) {
            $this->input->setOption('users', true);

            $this->input->setOption('for-tenant', false);
        }

        if ($this->option('for-tenant') !== false) {
            $this->input->setOption('for-tenant', true);
        }

        parent::handle();
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        $users = $this->option('users');
        $forTenants = $this->option('for-tenant');

        if ($users === false) {
            $this->creator->setForTenants($forTenants);
            $this->creator->setForUsers(false);
        }

        if ($forTenants === false) {
            $this->creator->setForUsers($users);
            $this->creator->setForTenants(false);
        }

        if ($forTenants !== false && $this->driverHasDatabaseManager()) {
            $this->input->setOption('path',
                database_path('migrations' . DIRECTORY_SEPARATOR . 'tenant')
            );

            $this->input->setOption('realpath', true);
        }

        return parent::getMigrationPath();
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge([
            ['for-tenant', null, InputOption::VALUE_OPTIONAL, 'Creates a migration that is related to the tenant', true],

            ['users', null, InputOption::VALUE_OPTIONAL, 'Creates a migration for tenant users relation', false],

            ['multi', null, InputOption::VALUE_OPTIONAL, 'Creates a migration for a multi-database tenant', false],
        ], parent::getOptions());
    }

    /**
     * Get a collection of new options.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function addMoreOptions()
    {
        $options = $this->getOptions();

        $collection = collect($options)->map(function ($option) {
            list($token, $shortcut, $inputOption, $description, $default) = $option;

            return new InputOption($token, $shortcut, $inputOption, $description, $default);
        });

        return $collection;
    }
}
