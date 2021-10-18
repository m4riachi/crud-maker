<?php

namespace M4riachi\CrudMaker\Utilities;

class Helper
{
    public static function checkFileExist(String $path): bool {
        return file_exists($path);
    }

    public static function getFileContent(String $path, bool $json = true) {
        $content = file_get_contents($path);

        if ($json)
            return json_decode($content, true);

        return $content;
    }
}