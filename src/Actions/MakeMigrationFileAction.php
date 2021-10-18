<?php

namespace M4riachi\CrudMaker\Actions;

use Illuminate\Database\Console\Migrations\TableGuesser;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use M4riachi\CrudMaker\Console\CrudMaker;
use M4riachi\CrudMaker\Utilities\Helper;

class MakeMigrationFileAction
{
    protected $files;
    protected $crudMaker;

    public function __construct(Filesystem $files, CrudMaker $crudMaker) {
        $this->files = $files;
        $this->crudMaker = $crudMaker;
    }

    public function execute(String $model, array $columns) {
        $this->ensureMigrationDoesntAlreadyExist($model, $this->getMigrationPath());

        $migration = [];
        foreach ($columns as $column) {
            if (in_array($column['name'], ['id', 'uuid'])) {
                $migration[] = "\$table->{$column['name']}();";
            }
            else {
                $type = explode(':', $column['type']);
                $migration[] = "\$table->{$type[0]}('{$column['name']}', {$type[1]});";
            }
        }
        $migration[] = '$table->timestamps();';

        $stub = $this->populateStub($model, $migration, $this->files->get(__DIR__ . '/../Stubs/migration.stub'));

        $this->files->put(
            $this->getPath($model), $stub
        );

        $this->crudMaker->info("<info>Created Migration:</info> {$model}");
    }

    protected function ensureMigrationDoesntAlreadyExist($model, $migrationPath = null)
    {
        if (! empty($migrationPath)) {
            $migrationFiles = $this->files->glob($migrationPath.'/*.php');

            foreach ($migrationFiles as $migrationFile) {
                $this->files->requireOnce($migrationFile);
            }
        }

        if (class_exists($className = $this->getClassName($model))) {
            $this->crudMaker->error("Migration {$className} class already exists.");
            die();
        }
    }

    protected function getPlural($model) {
        return Str::plural($model);
    }

    protected function getFileName($model) {
        return "create_" . $this->getPlural($model) . "_table";
    }

    protected function getClassName($model)
    {
        return Str::studly($this->getFileName($model));
    }

    protected function getMigrationPath() {
        return config('crud-maker.path.migrations');
    }

    protected function getPath($model)
    {
        return $this->getMigrationPath() . date('Y_m_d_His') . "_create_" . $this->getPlural($model) . "_table.php";
    }

    protected function populateStub($model, $migration, string $stub)
    {
        return str_replace(['{{ class }}', '{{ table }}', '{{ columns }}'], [
            $this->getClassName($model),
            $this->getPlural($model),
            implode("\n\t\t\t", $migration)
        ], $stub);
    }
}