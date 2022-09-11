<?php

namespace App\Http\Api\Auth;

use App\Http\ControllerApi;
use App\Service\AccessTokenService;
use DI\DependencyException;
use DI\NotFoundException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

class Token extends ControllerApi
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws OAuthServerException
     */
    public function index(Request $request, Response $response): Response
    {
        /** @var AuthorizationServer $authorizationServer */
        $authorizationServer = $this->container->get(AuthorizationServer::class);
        $accessTokenService = $this->container->get(AccessTokenService::class);

        $data = $request->getParsedBody();

        $client = $accessTokenService->getClientByGrant($data);

        $payload = [];
        $payload['grant_type'] = $data['grant_type'];
        $payload['client_id'] = $client->first()->identifier;
        $payload['client_secret'] = $client->first()->secret;

        if ($data['grant_type'] === 'password') {
            $payload['username'] = $data['username'];
            $payload['password'] = $data['password'];
        }

        $request = $request->withParsedBody($payload);

        return $authorizationServer->respondToAccessTokenRequest($request, $response);
    }
}