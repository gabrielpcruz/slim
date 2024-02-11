<?php

namespace App\Slim\File;

class File
{
    /**
     * @param string $path
     * @return void
     */
    public static function createPathIfNotExists(string $path): void
    {
        if (!self::isDirectory($path)) {
            mkdir($path, 0775, true);
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * @param string $path
     * @return string
     */
    public static function getSymbolicFilePath(string $path): string
    {
        return str_replace('/var/www/html/storage', '', $path);
    }
}
