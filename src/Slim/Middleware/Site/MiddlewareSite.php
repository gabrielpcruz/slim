<?php

namespace App\Slim\Middleware\Site;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Slim\Middleware\Middleware;

abstract class MiddlewareSite extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!is_api($request)) {
            return $this->handle($request, $handler);
        }

        return $handler->handle($request);
    }
}
