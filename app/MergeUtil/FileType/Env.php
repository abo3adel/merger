<?php

namespace App\MergeUtil\FileType;

use App\MergeUtil\Merger;
use Dotenv\Dotenv;

class Env extends Merger
{
    /**
     * merge two files with same names
     *
     * @param string $file
     * @param string $stubsDir
     * @return void
     */
    public function merge(string $file, string $stubsDir): void
    {
        $stubFile = $this->readFile($file, $stubsDir);

        $baseFile = $this->readFile($file, $this->userDir);

        if (
            !$this->handleFiles($stubFile, $baseFile, $file, 'ENV')
        ) {
            return;
        }
    
        $baseFile = $this->force ?
            array_merge($baseFile, $stubFile) :
            $baseFile + $stubFile;

        $output = [];
        foreach ($baseFile as $key => $val) {
            $output[] = strpos($val, ' ') === false ?
                $key . '=' . $val :
                $key . '="' . $val . '"';
        }

        $this->writeToFile($file, $output);
    }

    /**
     * get file contents as php array
     *
     * @param string $file
     * @return array|null
     */
    protected function getContent(string $file)
    {
        return Dotenv::createArrayBacked(getcwd())->load();
    }
}
