<?php

use App\App;

if (!function_exists('turnNameSpacePathIntoArray')) {
    function turnNameSpacePathIntoArray($nameSpacePath, $namespace, $excludeItems = [], $excludePaths = []): array
    {
        $items = [];

        $pathsToExclude = ['.', '..'];

        foreach ($excludePaths as $path) {
            $pathsToExclude[] = $path;
        }

        foreach (scandir($nameSpacePath) as $class) {
            if (!in_array($class, $pathsToExclude) && (!empty($excludeItems) && !in_array($class, $excludeItems))) {
                $items[] = $namespace . str_replace('.php', '', $class);
            }
        }

        return $items;
    }
}

