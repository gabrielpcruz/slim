<?php

namespace App\Slim\Middleware\Api;

use DomainException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Slim\Middleware\Middleware;

abstract class MiddlewareApi extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (is_api($request)) {
            return $this->handle($request, $handler);
        }

        throw new DomainException("Only api allowed!");
    }
}
