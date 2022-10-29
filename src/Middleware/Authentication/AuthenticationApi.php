<?php

namespace App\Middleware\Authentication;

use App\App;
use App\Repository\User\AccessTokenRepository;
use App\Repository\User\ClientRepository;
use DI\DependencyException;
use DI\NotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Slim\Exception\HttpUnauthorizedException;

class AuthenticationApi extends Authentication
{
    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     * @throws OAuthServerException
     * @throws ReflectionException
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function authenticate(ServerRequestInterface $request): ServerRequestInterface
    {
        $oauth2PublicKey = App::settings()->get('file.oauth_public');

        /** @var AccessTokenRepository $accessTokenRepository */
        $accessTokenRepository = $this->repositoryManager->get(AccessTokenRepository::class);

        $server = new ResourceServer(
            $accessTokenRepository,
            $oauth2PublicKey
        );

        $request = $server->validateAuthenticatedRequest($request);
        $clientRepository = $this->repositoryManager->get(ClientRepository::class);

        $client = $clientRepository->findOneBy([
            'id' => $request->getAttribute('oauth_client_id'),
        ]);

        if (!$client) {
            throw new HttpUnauthorizedException($request);
        }

        return $request->withAttribute('oauth_client_id', $client->id);
    }
}