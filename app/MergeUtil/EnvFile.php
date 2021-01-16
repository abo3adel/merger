<?php

namespace App\MergeUtil;

use Dotenv\Dotenv;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class EnvFile extends Merger
{
    /**
     * merge two files with same names
     *
     * @param string $file
     * @param string $stubsDir
     * @return void
     */
    public function merge(string $file, string $stubsDir): void {
        $stubFile = $this->readFile($file, $stubsDir);
        
        $baseFile = $this->readFile($file, $this->userDir);

        if (is_null($stubFile) || is_null($baseFile)) {
            printf("\e[1;37;41mError: Unable to parse the Env file: %s\e[0m\n\n", $file);
            return;
        }

        $baseFile = $this->force ?
            array_merge($baseFile, $stubFile) :
            $baseFile + $stubFile;

        $output = [];
        foreach ($baseFile as $key => $val) {
            $output[] = strpos($val, ' ') === false ?
                $key . '=' . $val :
                $key . '="' . $val .'"';
        }

        File::put(
            $this->userDir. DIRECTORY_SEPARATOR . $file,
            implode("\n", $output)
        );
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