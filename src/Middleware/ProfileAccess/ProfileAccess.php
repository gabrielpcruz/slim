<?php

namespace App\Middleware\ProfileAccess;

use App\Exception\UserNotAllowedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class ProfileAccess implements MiddlewareInterface, ProfileAccessInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws UserNotAllowedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->allowed($request)) {
            $handler->handle($request);
        }

        throw new UserNotAllowedException();
    }
}