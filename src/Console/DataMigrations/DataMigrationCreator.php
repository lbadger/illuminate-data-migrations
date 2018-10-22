<?php

namespace Lbadger\Database\Console\DataMigrations;

use Closure;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;

class DataMigrationCreator extends MigrationCreator
{

    protected $customStub = '';

    public function setCustomStub($stub)
    {
        $this->customStub = $stub;
    }

    /**
     * Get the migration stub file.
     *
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    protected function getStub($table, $create)
    {
        if (!empty($this->customStub)) {
            return $this->files->get(database_path('/stubs') . '/' . $this->customStub . '.stub');
        }

        return parent::getStub($table, $create);
    }
}
