<?php

namespace App\Message\Exception\System;

use App\Message\Message;

class MessageExceptionSystem extends Message
{
    public const string MES0001 = 'User not allowed access.';
}
