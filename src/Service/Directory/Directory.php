<?php

namespace App\Service\Directory;

class Directory
{
    public static function turnNameSpacePathIntoArray(
        $nameSpacePath,
        $namespace,
        $excludeFiles = [],
        $excludePaths = []
    ): array
    {
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
}
