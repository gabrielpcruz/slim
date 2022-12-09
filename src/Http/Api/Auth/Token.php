<?php

namespace App\Http\Api\Auth;

use App\Http\ApiController;
use App\Service\Token\AccessToken;
use DI\DependencyException;
use DI\NotFoundException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

class Token extends ApiController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var AuthorizationServer $authorizationServer */
        $authorizationServer = $this->container->get(AuthorizationServer::class);

        /** @var AccessToken $accessTokenService */
        $accessTokenService = $this->container->get(AccessToken::class);

        $data = $request->getParsedBody();

        $client = $accessTokenService->getClientByGrant($data);

        $payload = [];
        $payload['grant_type'] = $data['grant_type'];
        $payload['client_id'] = $client->identifier;
        $payload['client_secret'] = $client->secret;

        if ($data['grant_type'] === 'password') {
            $payload['username'] = $data['username'];
            $payload['password'] = $data['password'];
        }

        if ($data['grant_type'] === 'refresh_token') {
            $payload['refresh_token'] = $data['refresh_token'];
        }

        $request = $request->withParsedBody($payload);

        try {
            return $authorizationServer->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $response->getBody()->write($exception->getMessage());

            return $response->withStatus(500);
        }
    }
}