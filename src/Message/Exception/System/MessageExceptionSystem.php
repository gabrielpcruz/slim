<?php

namespace App\Message\Exception\System;

use App\Message\Message;

class MessageExceptionSystem extends Message
{
    public const MES0001 = 'User not allowed access.';
}