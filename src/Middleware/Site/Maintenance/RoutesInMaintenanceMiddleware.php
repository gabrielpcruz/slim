<?php

namespace App\Middleware\Site\Maintenance;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Slim\Middleware\Middleware;

class RoutesInMaintenanceMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (App::isRouteInMaintenance($request)) {
            return redirect('/route_maintenance');
        }

        return $handler->handle($request);
    }
}
