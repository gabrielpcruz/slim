<?php

namespace App\Middleware\Authentication\Site;

use App\Enum\FlashMessage;
use App\Message\Exception\System\MessageExceptionSystem;
use App\Service\Session\Session;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationSite implements MiddlewareInterface
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
        if (!Session::isLoggedIn()) {
            flash()->addMessage(FlashMessage::ERROR, MessageExceptionSystem::MES0001);

            return redirect('/login');
        }

        return $handler->handle($request);
    }
}
