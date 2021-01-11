<?php

namespace App;

class Helpers
{
    const STUBS = 'stubs';

    /**
     * get stubs path
     *
     * @param string $path
     * @return string
     */
    public static function stubsPath(string $path = ''): string
    {
        $sep = empty($path) ? '' : DIRECTORY_SEPARATOR;
        return storage_path(self::STUBS . $sep . $path);
    }
}