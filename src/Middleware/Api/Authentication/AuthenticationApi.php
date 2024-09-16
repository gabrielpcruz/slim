<?php

namespace App\Middleware\Api\Authentication;

use App\App;
use App\Entity\User\ClientEntity;
use App\Repository\User\AccessTokenAbstractRepository;
use App\Repository\User\ClientAbstractRepository;
use App\Slim\Middleware\Api\MiddlewareApi;
use DI\DependencyException;
use DI\NotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;
use Slim\Exception\HttpUnauthorizedException;
use App\Slim\Repository\RepositoryManager;

class AuthenticationApi extends MiddlewareApi
{
    /**
     * @var RepositoryManager
     */
    protected RepositoryManager $repositoryManager;

    /**
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

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
        /** @var string $oauth2PublicKey */
        $oauth2PublicKey = App::settings()->get('file.oauth_public');

        /** @var AccessTokenAbstractRepository $accessTokenRepository */
        $accessTokenRepository = $this->repositoryManager->get(AccessTokenAbstractRepository::class);

        $server = new ResourceServer(
            $accessTokenRepository,
            $oauth2PublicKey
        );

        $request = $server->validateAuthenticatedRequest($request);
        $clientRepository = $this->repositoryManager->get(ClientAbstractRepository::class);

        /** @var ClientEntity $client */
        $client = $clientRepository->findOneBy([
            'id' => $request->getAttribute('oauth_client_id'),
        ]);

        if (!$client) {
            throw new HttpUnauthorizedException($request);
        }

        return $request->withAttribute('oauth_client_id', $client->id);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws OAuthServerException
     * @throws ReflectionException
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->authenticate($request);

        return $handler->handle($request);
    }
}
