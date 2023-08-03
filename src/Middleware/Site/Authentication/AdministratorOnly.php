<?php

namespace App\Middleware\Site\Authentication;

use App\Enum\EnumProfile;
use App\Enum\FlashMessage;
use App\Message\Exception\System\MessageExceptionSystem;
use App\Middleware\Site\MiddlewareSite;
use App\Service\Session\Session;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AdministratorOnly extends MiddlewareSite
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
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->isAdministrator()) {
            Session::logout();

            return redirect('/login');
        }

        return $handler->handle($request);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     */
    private function isAdministrator(): bool
    {
        if (!Session::isLoggedIn()) {
            return false;
        }

        $usuario = Session::getUser();

        if (!$usuario) {
            return false;
        }

        if (!EnumProfile::isAdmin($usuario->profile()->first()->name)) {
            flash()->addMessage(FlashMessage::ERROR, MessageExceptionSystem::MES0001);
            return false;
        }

        return EnumProfile::isAdmin($usuario->profile()->first()->name);
    }
}
