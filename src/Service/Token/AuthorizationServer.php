<?php

namespace App\Service\Token;

use App\App;
use App\Repository\User\AccessTokenAbstractRepository;
use App\Repository\User\ClientAbstractRepository;
use App\Repository\User\RefreshTokenAbstractRepository;
use App\Repository\User\ScopeAbstractRepository;
use App\Repository\User\UserAbstractRepository;
use App\Slim\Repository\RepositoryManager;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use App\Slim\Service\AbstractService;

class AuthorizationServer extends AbstractService
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
     * @throws Exception
     */
    public function create(ContainerInterface $container): LeagueAuthorizationServer
    {
        /** @var string $oauth2PrivateKey */
        $oauth2PrivateKey = App::settings()->get('file.oauth_private');

        /** @var RepositoryManager $repositoryManager */
        $repositoryManager = App::container()->get(RepositoryManager::class);

        /** @var ClientAbstractRepository $clientRepository */
        $clientRepository = $repositoryManager->get(ClientAbstractRepository::class);

        /** @var ScopeAbstractRepository $scopeRepository */
        $scopeRepository = $repositoryManager->get(ScopeAbstractRepository::class);

        /** @var AccessTokenAbstractRepository $tokenRepository */
        $tokenRepository = $repositoryManager->get(AccessTokenAbstractRepository::class);

        $server = new LeagueAuthorizationServer(
            $clientRepository,
            $tokenRepository,
            $scopeRepository,
            $oauth2PrivateKey,
            $this->encryption_key
        );

        /** @var UserAbstractRepository $userRepository */
        $userRepository = $repositoryManager->get(UserAbstractRepository::class);

        /** @var RefreshTokenAbstractRepository $refreshTokenRepository */
        $refreshTokenRepository = $repositoryManager->get(RefreshTokenAbstractRepository::class);

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
