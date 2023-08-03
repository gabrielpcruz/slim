<?php

namespace App\Middleware\Authentication\Site;

use App\App;
use App\Enum\FlashMessage;
use App\Message\Exception\System\MessageExceptionSystem;
use App\Middleware\MiddlewareSite;
use App\Service\Session\Session;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationSite extends MiddlewareSite
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
        if (!App::settings()->get('system.maintenance') && !Session::isLoggedIn() && !App::isGuestRoute($request)) {
            flash()->addMessage(FlashMessage::ERROR, MessageExceptionSystem::MES0001);

            return redirect('/login');
        }

        return $handler->handle($request);
    }
}
