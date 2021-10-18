<?php

namespace M4riachi\CrudMaker\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use M4riachi\CrudMaker\Actions\MakeFilesAction;
use M4riachi\CrudMaker\Utilities\Helper;

class CrudMaker extends Command
{
    protected $signature = "crud-maker:generate";

    protected $description = "Generate a crud (Create, read, update, delete)";

    public function handle(Filesystem $filesystem) {
        $jsonPath = config('crud-maker.json_path');

        if (!Helper::checkFileExist($jsonPath)) {
            return $this->error("The json file doesn't exist");
        }

        $options = Helper::getFileContent($jsonPath);

        foreach ($options as $option) {
            MakeFilesAction::execute($filesystem, $this, $option);
        }
    }
}