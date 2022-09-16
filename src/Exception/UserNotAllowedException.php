<?php

namespace App\Exception;

use App\Message\Exception\System\MessageExceptionSystem;
use Throwable;

class UserNotAllowedException extends SlimException
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            MessageExceptionSystem::MES0001,
            $code,
            $previous
        );
    }
}