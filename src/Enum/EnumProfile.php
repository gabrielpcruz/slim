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
     * @var int
     */
    public const ADMINISTRATOR_ID = 1;

    /**
     * @var int
     */
    public const USER_ID = 2;

    /**
     * @param $profile
     * @return bool
     */
    public static function isAdmin($profile): bool
    {
        return self::ADMINISTRATOR === $profile;
    }
}