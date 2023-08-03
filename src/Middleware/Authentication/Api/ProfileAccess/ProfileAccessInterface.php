<?php

namespace App\Middleware\Authentication\Api\ProfileAccess;

use Psr\Http\Message\ServerRequestInterface;

interface ProfileAccessInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function allowed(ServerRequestInterface $request): bool;
}
