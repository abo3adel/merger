<?php

namespace App\MergeUtil;

use App\Helpers;
use Illuminate\Support\Facades\File;

abstract class Merger implements MergerInterface
{
    use OutputToConsole;
    
    protected $userDir;
    protected $force;
    protected $noAppend;

    public function setUserDir(string $dir): self
    {
        $this->userDir = $dir;
        return $this;
    }

    public function setForce(bool $force): self
    {
        $this->force = $force;
        return $this;
    }

    public function setNoAppend(bool $noAppend): self
    {
        $this->noAppend = $noAppend;
        return $this;
    }

    /**
     * read file content
     *
     * @param string $file
     * @param string|null $dir
     * @return array|null
     */
    protected function readFile(string $file, ?string $dir = null)
    {
        is_dir($dir) ? chdir($dir) : '';

        if (!file_exists($file)) return null;

        return $this->getContent($file);
    }

    /**
     * write content to provided file
     *
     * @param string $file
     * @param array|string $content
     * @param string|null $dir
     * @return void
     */
    protected function writeToFile(
        string $file,
        $content,
        ?string $dir = null
    ): void {
        $dir = $dir ?? $this->userDir;

        $content = is_array($content) ? $this->stringfyContent($content) : $content;

        File::put(
            $dir . DIRECTORY_SEPARATOR . $file,
            $content
        );
    }

    /**
     * show error if two files not exists or parsing have errors
     * and if permited append stub file into user directory 
     *
     * @param array|null $stubFile
     * @param array|null $baseFile
     * @param string $fileName
     * @param string $type
     * @return boolean
     */
    protected function handleFiles(
        ?array $stubFile,
        ?array $baseFile,
        string $fileName,
        string $type = ''
    ): bool {
        if (is_null($stubFile) && is_null($baseFile)) {
            printf("\e[1;37;41mError: Unable to parse the %s file: %s\e[0m%s", $type, $fileName, PHP_EOL);
            return false;
        } elseif (!is_null($stubFile) && is_null($baseFile)) {
            if ($this->noAppend) return true;
            // append stub file to user directory
            $dir = getcwd(); // store current directory

            chdir($this->userDir);

            $this->line("file ($fileName) not found in current directory, then it will be appended");
            $this->info('Appending file ('. $fileName .') to current directory');

            $this->writeToFile($fileName, $stubFile);
            // get back to previouse directory
            chdir($dir);
            return false;
        }
        return true;
    }

    /**
     * transform content into string
     *
     * @param array|string $content
     * @return void
     */
    protected function stringfyContent(array $content): string {
        return implode("\n", $content);
    }

    /**
     * get file contents as php array
     *
     * @param string $file
     * @return array|null
     */
    protected abstract function getContent(string $file);
}
