<?php

namespace App\Slim\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Middleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return Response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): Response
    {
        return $this->handle($request, $handler);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return Response
     */
    abstract public function handle(ServerRequestInterface $request, RequestHandlerInterface $handler): Response;
}
