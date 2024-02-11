<?php

namespace App\Enum;

class EnumProfile
{
    /**
     * @var string
     */
    public const string ADMINISTRATOR = 'ADMINISTRATOR';

    /**
     * @var string
     */
    public const string USER = 'USER';

    /**
     * @var int
     */
    public const int ADMINISTRATOR_ID = 1;

    /**
     * @var int
     */
    public const int USER_ID = 2;

    /**
     * @param string $profile
     * @return bool
     */
    public static function isAdmin(string $profile): bool
    {
        return self::ADMINISTRATOR === $profile;
    }
}
