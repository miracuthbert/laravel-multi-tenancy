<?php

namespace Miracuthbert\Multitenancy\Console;

use Symfony\Component\Console\Input\InputOption;

trait PackageSetupTrait
{
    /**
     * A thank you message.
     *
     * @return void
     */
    public function showThanks()
    {
        $this->line('');

        $this->info(__(
            sprintf('Hey, there thanks for using this package.'
            )
        ));

        $this->info(__(
            sprintf('Show your support by being a Star gazer at: %s',
                'https://github.com/miracuthbert/laravel-multi-tenancy'
            )
        ));
    }

    /**
     * Setup a tenant model and its related migrations.
     *
     * @param string|null $model
     * @param bool $multi
     * @param bool $columnsOnly
     * @return void
     */
    public function setupTenantModel($model = null, $multi = false, $columnsOnly = false)
    {
        if ($model === null) {
            return;
        }

        $this->call('tenancy:model', [
            'name' => $model,
            '-m' => true,
            '--multi' => $multi,
            '--columns-only' => $columnsOnly,
        ]);
    }

    /**
     * Create a migration file with tenant columns only.
     *
     * @param string $table
     * @return void
     */
    public function setupTenantColumnsOnly($table)
    {
        $this->call('tenancy:migration', [
            'name' => "add_tenant_columns_to_{$table}_table",
            '--table' => $table,
            '--for-tenant' => false,
        ]);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = array_merge(parent::getOptions(), [
            ['multi', null, InputOption::VALUE_OPTIONAL, 'Indicates if the generated file should be for a multi-database tenant', false],

            ['model', null, InputOption::VALUE_OPTIONAL, 'Create a new Eloquent based model class for tenant'],

            ['columns-only', null, InputOption::VALUE_OPTIONAL, 'Indicates if the created migration should only add tenant columns to an existing table', false],

            ['table', null, InputOption::VALUE_OPTIONAL, 'Create a migration file with tenant columns only for an existing table'],
        ]);

        return $options;
    }
}
