<?php

namespace Miracuthbert\Multitenancy\Traits\Console;

use Symfony\Component\Console\Input\InputOption;

trait AcceptsMultipleTenants
{
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = array_filter(parent::getOptions(), function ($option) {
            return !in_array('class', $option);
        });

        $options = array_merge(
            $options, [
                ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root tenant seeder', 'TenantDatabaseSeeder'],
                ['tenants', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, '', null],
            ]
        );

        return $options;
    }
}
