<?php

namespace OAuth2ServerExamples\Entities;

use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity implements UserEntityInterface
{
    public function getIdentifier(): int
    {
        return 1;
    }
}