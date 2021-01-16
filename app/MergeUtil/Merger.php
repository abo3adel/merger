<?php

namespace App\MergeUtil;

use App\Helpers;

abstract class Merger implements MergerInterface
{
    protected $userDir;
    protected $force;

    public function setUserDir(string $dir): self {
        $this->userDir = $dir;
        return $this;
    }

    public function setForce(bool $force): self {
        $this->force = $force;
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

        if(!file_exists($file)) return null;

        return $this->getContent($file);
    }

    /**
     * get file contents as php array
     *
     * @param string $file
     * @return array|null
     */
    protected abstract function getContent(string $file);
}