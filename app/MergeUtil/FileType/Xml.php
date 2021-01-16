<?php

namespace App\MergeUtil\FileType;

use App\MergeUtil\Merger;
use Exception;
use Illuminate\Support\Facades\File;
use LaLit\XML2Array;
use LaLit\Array2XML;

class Xml extends Merger
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
            !$this->handleFiles($stubFile, $baseFile, $file, 'XML')
        ) {
            return;
        }

        $nodeName = array_keys($baseFile)[0];

        $baseFile = array_merge($baseFile, $stubFile);

        File::put(
            $this->userDir . DIRECTORY_SEPARATOR . $file,
            (Array2XML::createXML($nodeName, $baseFile[$nodeName]))->saveXML()
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
        $value = null;

        try {
            $value = XML2Array::createArray(file_get_contents($file));;
        } catch (Exception $e) {
        }

        return $value;
    }

    /**
     * transform content into string
     *
     * @param array|string $content
     * @return string
     */
    protected function stringfyContent($content): string
    {
        return (Array2XML::createXML('node', $content))->saveXML();
    }
}
