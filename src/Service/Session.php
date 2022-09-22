<?php

namespace App\Service;

use App\Entity\User\UserEntity;

class Session
{
    /**
     * @return void
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        if (is_null($_SESSION)) {
            return false;
        }

        return count($_SESSION) && isset($_SESSION['user']) && $_SESSION['user'];
    }

    /**
     * @param UserEntity $user
     * @return bool
     */
    public static function sessionStart(UserEntity $user): bool
    {
        $_SESSION['user'] = $user;

        return true;
    }

    /**
     * @return bool
     */
    public static function logout(): bool
    {
        session_unset();
        return true;
    }
}