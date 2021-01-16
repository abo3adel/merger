<?php

namespace App\MergeUtil;

use Illuminate\Support\Facades\File;
use NunoMaduro\Collision\ConsoleColor;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;


class YamlFile extends Merger
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

        if (is_null($stubFile) || is_null($baseFile)) {
            return;
        }

        $baseFile = $this->force ?
            array_merge($baseFile, $stubFile) :
            $baseFile + $stubFile;

        File::put(
            $this->userDir . DIRECTORY_SEPARATOR . $file,
            Yaml::dump($baseFile)
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
            $value = Yaml::parseFile($file);
        } catch (ParseException $exception) {
            printf("\e[1;37;41mError: Unable to parse the YAML file: %s\e[0m\n\n", $file);
        }

        return $value;
    }
}
