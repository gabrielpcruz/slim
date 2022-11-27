<?php

namespace App\Service\Session;

use App\Entity\User\UserEntity;

class Session
{
    /**
     * @var int
     */
    private static int $SESSION_TIME = 1800;

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
     * @return void
     */
    private static function sessionTimeMonitor()
    {
        if (
            isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity'] > self::$SESSION_TIME)
        ) {
            self::logout();
        }

        $_SESSION['last_activity'] = time();
    }

    /**
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        self::sessionTimeMonitor();

        if (is_null($_SESSION)) {
            return false;
        }

        return count($_SESSION) && isset($_SESSION['user']) && $_SESSION['user'];
    }

    /**
     * @param UserEntity $user
     * @return bool
     */
    public static function user(UserEntity $user): bool
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
        session_destroy();

        return true;
    }

    /**
     * @return UserEntity|null
     */
    public static function getUser(): ?UserEntity
    {
        return Session::isLoggedIn() ? $_SESSION['user'] : null;
    }
}