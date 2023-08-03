<?php

namespace App\Middleware\Api\Authentication\ProfileAccess;

use Psr\Http\Message\ServerRequestInterface;

interface ProfileAccessInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function allowed(ServerRequestInterface $request): bool;
}
