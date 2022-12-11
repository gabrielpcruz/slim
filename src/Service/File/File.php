<?php

namespace App\Service\File;

class File
{
    /**
     * @param $path
     * @return void
     */
    public static function createPathIfNotExists($path): void
    {
        if (!self::isDirectory($path)) {
            mkdir($path, 0775, true);
        }
    }

    /**
     * @param $path
     * @return bool
     */
    public static function isDirectory($path): bool
    {
        return is_dir($path);
    }

    /**
     * @param $path
     * @return string
     */
    public static function getSymbolicFilePath($path): string
    {
        return str_replace('/var/www/html/storage', '', $path);
    }
}
