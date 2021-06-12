<?php

namespace Miracuthbert\Multitenancy\Console;

use Illuminate\Database\Migrations\MigrationCreator as BaseMigrationCreator;
use Illuminate\Filesystem\Filesystem;

class MigrationCreator extends BaseMigrationCreator
{
    /**
     * Indicate if for tenant stubs should be used.
     *
     * @var bool
     */
    public $forTenants = true;

    /**
     * Indicate if user migration stubs should be used.
     *
     * @var bool
     */
    public $forUsers = false;

    /**
     * Create a new migration creator instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param  string  $customStubPath
     * @return void
     */
    public function __construct(Filesystem $files, $customStubPath = '')
    {
        if (property_exists(BaseMigrationCreator::class, 'customStubPath')) {
            parent::__construct($files, $customStubPath);
        } else {
            parent::__construct($files);
        }

        $this->files = $files;
    }

    /**
     * Set whether the migration is used placed under tenant migrations.
     *
     * @param bool $forTenants
     */
    public function setForTenants(bool $forTenants)
    {
        $this->forTenants = $forTenants;
    }

    /**
     * Set whether the migration is for tenant users relation.
     *
     * @param bool $forUsers
     *
     */
    public function setForUsers(bool $forUsers)
    {
        $this->forUsers = $forUsers;
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function stubPath()
    {
        if ($this->forTenants === true) {
            return __DIR__ . '/stubs/migrations/tenant';
        }

        if ($this->forUsers === true) {
            return __DIR__ . '/stubs/migrations/users';
        }

        return __DIR__ . '/stubs/migrations';
    }
}
