<?php 

namespace Miracuthbert\Multitenancy\Traits\Console;

/**
 * A set of methods to complement migrate commands.
 */
trait MigrationHelperTrait
{
    /**
     * Get all of the migration paths.
     *
     * @return array
     */
    protected function getMigrationPaths()
    {
        // Here, we will check to see if a path option has been defined. If it has we will
        // use the path relative to the root of the installation folder so our database
        // migrations may be run for any customized path from within the application.
        if ($this->input->hasOption('path') && $this->option('path')) {
            return collect($this->option('path'))->map(function ($path) {
                return ! $this->usingRealPath()
                                ? $this->laravel->basePath().'/'.$path
                                : $path;
            })->all();
        }

        return [
            database_path('migrations/tenant')
        ];
    }
}
