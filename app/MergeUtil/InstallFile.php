<?php

namespace App\MergeUtil;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class InstallFile extends Merger
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

        if (is_null($stubFile)) {
            printf("\e[1;37;41mError: Unable to parse the yaml file: %s\e[0m\n\n", $file);
            return;
        }

        [$composer, $composerDev] = $this->extract($stubFile, 'composer');
        [$npm, $npmDev] = $this->extract($stubFile, 'npm');
        [$yarn, $yarnDev] = $this->extract($stubFile, 'yarn');        
        
        $command = '';
        if (count($composer)) {
            $command .= 'composer require ' . implode(' ', $composer) . ' && ';
        }
        if (count($composerDev)) {
            $command .= 'composer require --dev ' . implode(' ', $composerDev) . ' && ';
        }
        if (count($npm)) {
            $command .= 'npm install ' . implode(' ', $npm) . ' && ';   
        }
        if (count($npmDev)) {
            $command .= 'npm install --save-dev ' . implode(' ', $npmDev) . ' && ';   
        }
        if (count($yarn)) {
            $command .= 'yarn add ' . implode(' ', $yarn) . ' && ';   
        }
        if (count($yarnDev)) {
            $command .= 'yarn add --dev ' . implode(' ', $yarnDev) . ' && ';   
        }

        $command = preg_replace("/\s\&\&\s$/", "", $command);

        chdir($this->userDir);

        exec($command);
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
            $value = Yaml::parseFile($file);
        } catch (ParseException $exception) {
            printf("\e[1;37;41mError: Unable to parse the YAML file: %s\e[0m\n\n", $file);
        }

        return $value;
    }

    /**
     * extrack package names
     *
     * @param array $stub
     * @param string $pkg
     * @return array
     */
    private function extract(array $stub, string $pkg): array
    {
        if (!isset($stub[$pkg])) return [[], []];

        $output = $outputDev = [];
        foreach ($stub[$pkg] as $key => $val) {
            if (is_array($val)) {
                $outputDev = $val['dev'];
                continue;
            }
            $output[] = $val;
        }

        return [$output, $outputDev];
    }
}