<?php

namespace App\MergeUtil;

use Exception;
use Illuminate\Support\Facades\File;
use LaLit\XML2Array;
use LaLit\Array2XML;

class XmlFile extends Merger
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

        $nodeName = array_keys($baseFile)[0];

        if (is_null($stubFile) || is_null($baseFile)) {
            printf("\e[1;37;41mError: Unable to parse the XML file: %s\e[0m\n\n", $file);
            return;
        }

        $baseFile = array_merge($baseFile, $stubFile);

        File::put(
            $this->userDir. DIRECTORY_SEPARATOR . $file,
            (Array2XML::createXML($nodeName, $baseFile[$nodeName]))->saveXML()
        );
    }

    /**
     * get file contents as php array
     *
     * @param string $file
     * @param string|null $dir
     * @return array|null
     */
    private function readFile(string $file, ?string $dir = null)
    {
        is_dir($dir) ? chdir($dir) : '';

        $value = null;

        try {
            $value = XML2Array::createArray(file_get_contents($file));;
        } catch(Exception $e) {
        }

        return $value;
    }
}