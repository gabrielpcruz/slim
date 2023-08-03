<?php

namespace App\Middleware\Authentication\Api\ProfileAccess;

use App\App;
use App\Entity\User\UserEntity;
use App\Exception\UserNotAllowedException;
use App\Middleware\MiddlewareApi;
use App\Repository\RepositoryManager;
use App\Repository\User\UserRepository;
use DI\DependencyException;
use DI\NotFoundException;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;

abstract class ProfileAccess extends MiddlewareApi implements ProfileAccessInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return UserEntity
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws OAuthServerException
     */
    protected function getUser(ServerRequestInterface $request): UserEntity
    {
        $repositoryManager = App::container()->get(RepositoryManager::class);

        /** @var BearerTokenValidator $bearerValidator */
        $bearerValidator = App::container()->get(BearerTokenValidator::class);

        /** @var UserRepository $userRepository */
        $userRepository = $repositoryManager->get(UserRepository::class);

        $request = $bearerValidator->validateAuthorization($request);

        /** @var UserEntity $user */
        $user = $userRepository->findOneBy(
            ['client_id' => $request->getAttribute('oauth_client_id')]
        );

        return $user;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws UserNotAllowedException
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->allowed($request)) {
            return $handler->handle($request);
        }

        throw new UserNotAllowedException();
    }
}
