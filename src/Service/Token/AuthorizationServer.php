<?php

namespace App\Service\Token;

use App\App;
use App\Repository\User\AccessTokenRepository;
use App\Repository\User\ClientRepository;
use App\Repository\User\RefreshTokenRepository;
use App\Repository\User\ScopeRepository;
use App\Repository\User\UserRepository;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use App\Slim\Repository\RepositoryManager;

class AuthorizationServer
{
    private string $encryption_key = '89v787Ui4pj5HnUGTV29yXfvNA12BmgUozhBVv1uFMs=';

    /**
     * @var int
     */
    private int $tokenExpiresInMinutes = 0;

    /**
     * @var int
     */
    private int $tokenExpiresInHours = 1;

    /**
     * @var int
     */
    private int $tokenExpiresInDays = 0;

    /**
     * @param ContainerInterface $container
     * @return LeagueAuthorizationServer
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws Exception
     */
    public function create(ContainerInterface $container): LeagueAuthorizationServer
    {
        /** @var string $oauth2PrivateKey */
        $oauth2PrivateKey = App::settings()->get('file.oauth_private');

        /** @var RepositoryManager $repositoryManager */
        $repositoryManager = App::container()->get(RepositoryManager::class);

        /** @var ClientRepository $clientRepository */
        $clientRepository = $repositoryManager->get(ClientRepository::class);

        /** @var ScopeRepository $scopeRepository */
        $scopeRepository = $repositoryManager->get(ScopeRepository::class);

        /** @var AccessTokenRepository $tokenRepository */
        $tokenRepository = $repositoryManager->get(AccessTokenRepository::class);

        $server = new LeagueAuthorizationServer(
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
            datePeriod(
                ($this->tokenExpiresInDays ?? 0),
                ($this->tokenExpiresInHours ?? 0),
                ($this->tokenExpiresInMinutes ?? 0),
            )
        );

        $refreshTokenGrant = new RefreshTokenGrant($refreshTokenRepository);
        $server->enableGrantType($refreshTokenGrant, $refreshTokenExpiryTime);

        return $server;
    }
}
