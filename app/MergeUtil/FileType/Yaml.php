<?php

namespace App\MergeUtil\FileType;

use App\MergeUtil\Merger;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml as YamlParser;

class Yaml extends Merger
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
            !$this->handleFiles($stubFile, $baseFile, $file, 'YAML')
        ) {
            return;
        }

        $baseFile = $this->force ?
            array_merge($baseFile, $stubFile) :
            $baseFile + $stubFile;

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
        $value = null;

        try {
            $value = YamlParser::parseFile($file);
        } catch (ParseException $exception) {
            printf("\e[1;37;41mError: Unable to parse the YAML file: %s\e[0m\n\n", $file);
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
        return YamlParser::dump($content);
    }
}
