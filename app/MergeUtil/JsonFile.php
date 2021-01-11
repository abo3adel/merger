<?php

namespace App\MergeUtil;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class JsonFile extends Merger
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
        $output = $baseFile->toArray();

        /**
         * array of keys that it`s value should be replaced
         */
        $replaceAbleKeys = [
            'require' => 0,
            'require-dev' => 0,
            'dependencies' => 0,
            'devDependencies' => 0,
            'config' => 0,
        ];

        /**
         * array of keys that it`s keys should not be replaced
         * but instead append new values with old values
         */
        $reservedKeys = [
            'autoload' => 0,
            'autoload-dev' => 0,
            'extra' => 0,
            'scripts' => 0,
        ];

        foreach ($stubFile->toBase() as $key => $val) {
            if (isset($replaceAbleKeys[$key])) {
                $this->append($output[$key], $val, true);
            } elseif (isset($reservedKey[$key])) {
                $this->append($output[$key], $val);
            } else {
                $output[$key] = $val;
            }
        }

        File::put(
            $this->userDir. DIRECTORY_SEPARATOR . $file,
            json_encode($output, JSON_PRETTY_PRINT)
        );
    }

    /**
     * get file contents as php array
     *
     * @param string $file
     * @param string|null $dir
     * @return Collection
     */
    private function readFile(string $file, ?string $dir = null): Collection
    {
        is_dir($dir) ? chdir($dir) : '';
        return collect(json_decode(file_get_contents($file)));
    }

    /**
     * append new content to user provided
     *
     * @param array|object $outputKey
     * @param array|object $val
     * @param boolean $replace
     * @return void
     */
    private function append(
        &$outputKey,
        $val,
        bool $replace = false
    ): void {
        // cast into array
        $outputKey = (array) $outputKey;
        $val = (array) $val;

        if ($replace) {
            /**
             * used with keys that it`s value should be replaced
             */
            $outputKey = $this->force ? array_merge($outputKey, $val) : $outputKey + $val;
        } else {
            /**
             * used with keys that it`s value should not be replaced
             * but instead append new values to it
             */
            $outputKey = array_merge_recursive($outputKey, $val);
        }
    }
}