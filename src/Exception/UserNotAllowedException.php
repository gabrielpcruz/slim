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
    public function __construct(
        string $message = MessageExceptionSystem::MES0001,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
