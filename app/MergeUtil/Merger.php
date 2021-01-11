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
}