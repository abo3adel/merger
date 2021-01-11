<?php

namespace App\MergeUtil;

interface MergerInterface {
    public function merge(string $file, string $stubsDir): void;
}