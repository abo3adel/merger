<?php

namespace App\MergeUtil\FileType;

use App\MergeUtil\Merger;

class Git extends Merger
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

        if (
            !$this->handleFiles($stubFile, $baseFile, $file, 'GIT')
        ) {
            return;
        }

        $baseFile = array_merge($baseFile, $stubFile);

        $this->writeToFile($file, $baseFile);
    }

   /**
    * get file contents as php array
    *
    * @param string $file
    * @return array|null
    */
    protected function getContent(string $file)
    {
        return explode("\n", file_get_contents($file));        
    }
}