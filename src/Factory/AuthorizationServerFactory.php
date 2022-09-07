<?php

namespace App\Factory;

use App\App;
use DateInterval;
use DI\DependencyException;
use DI\NotFoundException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use App\Repository\User\ClientRepository;
use App\Repository\User\AccessTokenRepository;
use App\Repository\User\RefreshTokenRepository;
use App\Repository\User\ScopeRepository;
use App\Repository\User\UserRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthorizationServerFactory implements FactoryInterface
{
    const ENCRYPTION_KEY = '89v787Ui4pj5HnUGTV29yXfvNA12BmgUozhBVv1uFMs=';

    /**
     * @param ContainerInterface $container
     * @return AuthorizationServer
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(ContainerInterface $container): AuthorizationServer
    {
        $oauth2PrivateKey = App::settings()->get('file.oauth_private');

        $clientRepository = new ClientRepository();
        $scopeRepository = new ScopeRepository();
        $tokenRepository = new AccessTokenRepository();
        $userRepository = new UserRepository();
        $refreshTokenRepository = new RefreshTokenRepository();

        $server = new AuthorizationServer(
            $clientRepository,
            $tokenRepository,
            $scopeRepository,
            $oauth2PrivateKey,
            self::ENCRYPTION_KEY
        );

        $grant = new PasswordGrant(
            $userRepository,
            $refreshTokenRepository
        );

        $refreshTokenTTL = new DateInterval('P1M');

        $grant->setRefreshTokenTTL($refreshTokenTTL); // refresh tokens will expire after 1 month

        // Enable the password grant on the server
        $server->enableGrantType(
            $grant,
            $refreshTokenTTL // access tokens will expire after 1 hour
        );

        $clientCredentialsGrant = new ClientCredentialsGrant();
        $clientCredentialsGrant->setRefreshTokenTTL($refreshTokenTTL);
        $server->enableGrantType(
            $clientCredentialsGrant,
            $refreshTokenTTL // access tokens will expire after 1 hour
        );

        return $server;
    }
}