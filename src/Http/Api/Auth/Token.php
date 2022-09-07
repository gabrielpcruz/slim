<?php

namespace App\Http\Api\Auth;

use App\Http\ControllerApi;
use DI\Annotation\Inject;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Token extends ControllerApi
{
    /**
     * @Inject
     *
     * @var AuthorizationServer
     */
    private AuthorizationServer $authorizationServer;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request, Response $response): Response
    {
        $this->authorizationServer = $this->container->get(AuthorizationServer::class);

        var_dump($this->authorizationServer);
        exit();

        return $this->responseJSON($response, []);
    }
}