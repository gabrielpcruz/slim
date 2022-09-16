<?php

namespace App\Enum;

class EnumProfile
{
    /**
     * @var string
     */
    public const ADMINISTRATOR = 'ADMINISTRATOR';

    /**
     * @var string
     */
    public const USER = 'USER';

    /**
     * @param $profile
     * @return bool
     */
    public static function isAdmin($profile): bool
    {
        return self::ADMINISTRATOR === $profile;
    }
}