<?php

namespace App\Service\Utils;

class ArrayUtil
{
    /**
     * @param array $items
     * @param string $field
     * @return array
     */
    public static function fromEntityArrayOnlyField(array $items, string $field): array
    {
        $new = [];

        foreach ($items as $key => $item) {
            $new[] = $item[$field];
        }

        return $new;
    }
}
