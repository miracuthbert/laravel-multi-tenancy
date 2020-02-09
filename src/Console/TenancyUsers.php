<?php

namespace Miracuthbert\Multitenancy\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class TenancyUsers extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration file for tenant users relation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setName('tenancy:users');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->createUsersMigration();
    }

    /**
     * Creates a migration file for tenant users relation.
     *
     * @return void
     */
    protected function createUsersMigration()
    {
        $table = $this->argument('name');

        $this->call('tenancy:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--for-tenant' => false,
            '--users' => true,
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the users table'],
        ];
    }
}
