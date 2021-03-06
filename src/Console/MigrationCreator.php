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
     * Get the migration stub file.
     *
     * @param  string|null $table
     * @param  bool        $create
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStub($table, $create)
    {
        if (is_null($table)) {
            $stub = $this->files->exists($customPath = $this->customStubPath ?? '' . '/migration.stub')
                ? $customPath
                : $this->stubPath() . '/migration.stub';
        } elseif ($create) {
            $stub = $this->files->exists($customPath = $this->customStubPath ?? '' . '/create.stub')
                ? $customPath
                : $this->stubPath() . '/create.stub';
        } else {
            $stub = $this->files->exists($customPath = $this->customStubPath ?? '' . '/update.stub')
                ? $customPath
                : $this->stubPath() . '/update.stub';
        }

        return $this->files->get($stub);
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
