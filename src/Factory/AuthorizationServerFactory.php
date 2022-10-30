<?php

namespace App\Factory;

use App\App;
use App\Repository\RepositoryManager;
use DateInterval;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use App\Repository\User\ClientRepository;
use App\Repository\User\AccessTokenRepository;
use App\Repository\User\RefreshTokenRepository;
use App\Repository\User\ScopeRepository;
use App\Repository\User\UserRepository;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class AuthorizationServerFactory implements FactoryInterface
{
    private string $encryption_key = '89v787Ui4pj5HnUGTV29yXfvNA12BmgUozhBVv1uFMs=';

    /**
     * @var string|int
     */
    private int $tokenExpiresInMinutes = 0;

    /**
     * @var string|int
     */
    private int $tokenExpiresInHours = 1;

    /**
     * @var string|int
     */
    private int $tokenExpiresInDays = 0;

    /**
     * @param ContainerInterface $container
     * @return AuthorizationServer
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws Exception
     */
    public function create(ContainerInterface $container): AuthorizationServer
    {
        $oauth2PrivateKey = App::settings()->get('file.oauth_private');

        /** @var RepositoryManager $repositoryManager */
        $repositoryManager = App::container()->get(RepositoryManager::class);

        /** @var ClientRepository $clientRepository */
        $clientRepository = $repositoryManager->get(ClientRepository::class);

        /** @var ScopeRepository $scopeRepository */
        $scopeRepository = $repositoryManager->get(ScopeRepository::class);

        /** @var AccessTokenRepository $tokenRepository */
        $tokenRepository = $repositoryManager->get(AccessTokenRepository::class);

        $server = new AuthorizationServer(
            $clientRepository,
            $tokenRepository,
            $scopeRepository,
            $oauth2PrivateKey,
            $this->encryption_key
        );

        /** @var UserRepository $userRepository */
        $userRepository = $repositoryManager->get(UserRepository::class);

        /** @var RefreshTokenRepository $refreshTokenRepository */
        $refreshTokenRepository = $repositoryManager->get(RefreshTokenRepository::class);

        $refreshTokenExpiryTime = datePeriod(0, 1);

        $passwordGrant = new PasswordGrant(
            $userRepository,
            $refreshTokenRepository
        );

        $passwordGrant->setRefreshTokenTTL($refreshTokenExpiryTime);

        // Enable the password grant on the server
        $server->enableGrantType(
            $passwordGrant,
            $this->getExpiresTokenInterval()
        );

        $refreshTokenGrant = new RefreshTokenGrant($refreshTokenRepository);
        $server->enableGrantType($refreshTokenGrant, $refreshTokenExpiryTime);

        return $server;
    }

    /**
     * @return DateInterval
     * @throws Exception
     */
    private function getExpiresTokenInterval(): DateInterval
    {
        $expressionPeriod = 'P0Y%sDT%sH%sM';

        $expressionPeriod = sprintf(
            $expressionPeriod,
            ($this->tokenExpiresInDays ?? 0),
            ($this->tokenExpiresInHours ?? 0),
            ($this->tokenExpiresInMinutes ?? 0),
        );

        return new DateInterval($expressionPeriod);
    }
}