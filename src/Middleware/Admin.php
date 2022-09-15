<?php

namespace App\Middleware;

use App\App;
use App\Entity\User\AccessTokenEntity;
use App\Repository\RepositoryManager;
use App\Repository\User\AccessTokenRepository;
use App\Repository\User\UserRepository;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Admin implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $authorization = $request->getHeader('Authorization');

        $token = str_replace('Bearer ', '', $authorization);

        /** @var RepositoryManager $repositoryManager */
        $repositoryManager = App::container()->get(RepositoryManager::class);

        /** @var AccessTokenRepository $repositoryManager */
        $accessTokenRepository = $repositoryManager->get(AccessTokenRepository::class);

        $oauth2PublicKey = App::settings()->get('file.oauth_public');

        $beareValidator = new BearerTokenValidator($accessTokenRepository);
        $beareValidator->setPublicKey(new CryptKey($oauth2PublicKey));

        /** @var UserRepository $userRepository */
        $userRepository = $repositoryManager->get(UserRepository::class);

        $request = $beareValidator->validateAuthorization($request);

        $token = $accessTokenRepository->findOneBy(['access_token' => $token]);

        $user = $userRepository->findById($request->getAttribute('oauth_user_id'));


        if ($user->profile_id === 1) {
            $handler->handle($request);
        }

        throw new \Exception("asas");
    }
}