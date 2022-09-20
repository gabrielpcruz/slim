<?php

namespace App\Middleware\Authentication;

use Psr\Http\Message\ServerRequestInterface;

interface AuthenticationInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function authenticate(ServerRequestInterface $request): ?ServerRequestInterface;
}