<?php

namespace App;

class Helpers
{
    /**
     * get stubs path
     *
     * @param string $path
     * @return string
     */
    public static function stubsPath(string $path = ''): string
    {
        $sep = empty($path) ? '' : DIRECTORY_SEPARATOR;
        return base_path('stubs'. $sep . $path);
    }
}