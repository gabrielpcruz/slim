<?php

namespace App\Middleware;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MaintenanceMiddleware extends Middleware
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
        $isSystemInMaintenance = App::settings()->get('system.maintenance');
        $isRouteMaintenance = App::isRouteEqualOf($request, '/maintenance');
        $isRouteLogin = App::isRouteEqualOf($request, '/login');

        if ($isSystemInMaintenance && (!$isRouteMaintenance || $isRouteLogin)) {
            return redirect('/maintenance');
        }

        if (!$isSystemInMaintenance && $isRouteMaintenance) {
            return redirect('/login');
        }

        return $handler->handle($request);
    }
}
