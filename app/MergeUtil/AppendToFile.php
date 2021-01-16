<?php

namespace App\MergeUtil;

use Illuminate\Support\Facades\File;

class AppendToFile extends Merger
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
            printf("\e[1;37;41mError: Unable to parse the file: %s\e[0m\n\n", $file);
            return;
        }

        $baseFile .= "\n" . $stubFile;

        File::put(
            $this->userDir. DIRECTORY_SEPARATOR . $file,
            $baseFile
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
        return file_get_contents($file);        
    }
}