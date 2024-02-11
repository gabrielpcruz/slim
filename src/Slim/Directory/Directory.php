<?php

namespace App\Slim\Directory;

use Generator;

class Directory
{
    /**
     * @param $nameSpacePath
     * @param $namespace
     * @param array $excludeFiles
     * @param array $excludePaths
     * @return array
     */
    public static function turnNameSpacePathIntoArray(
        $nameSpacePath,
        $namespace,
        array $excludeFiles = [],
        array $excludePaths = []
    ): array {
        $items = [];

        $pathsToExclude = ['.', '..'];

        foreach ($excludePaths as $path) {
            $pathsToExclude[] = $path;
        }

        foreach (scandir($nameSpacePath) as $class) {
            $isExcludePath = in_array($class, $pathsToExclude);
            $isExcludeFile = in_array($class, $excludeFiles);

            if (!$isExcludePath && !$isExcludeFile) {
                $possibleDirectory = $nameSpacePath . "/{$class}";

                if (!is_dir($possibleDirectory)) {
                    $items[] = $namespace . str_replace('.php', '', $class);
                } else {
                    $newNameSpace = ($namespace . $class . "\\");

                    $items = array_merge($items, self::turnNameSpacePathIntoArray(
                        $possibleDirectory,
                        $newNameSpace,
                        $excludeFiles,
                        $excludePaths
                    ));
                }
            }
        }

        return $items;
    }

    /**
     * @param $files
     * @return Generator
     */
    public static function getIterator($files): Generator
    {
        foreach ($files as $file) {
            yield $file;
        }
    }
}
