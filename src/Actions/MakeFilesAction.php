<?php

namespace M4riachi\CrudMaker\Actions;

use Illuminate\Filesystem\Filesystem;
use M4riachi\CrudMaker\Console\CrudMaker;

class MakeFilesAction
{
    public static function execute(Filesystem $filesystem, CrudMaker $crudMaker, array $option) {
        if (isset($option['columns'])) {
            (new MakeMigrationFileAction($filesystem, $crudMaker))->execute($option['model'], $option['columns']);
        }
    }
}