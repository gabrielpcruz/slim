<?php

namespace App\Middleware\ProfileAccess;

use App\App;
use App\Entity\User\UserEntity;
use App\Exception\UserNotAllowedException;
use App\Repository\RepositoryManager;
use App\Repository\User\UserRepository;
use DI\DependencyException;
use DI\NotFoundException;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;

abstract class ProfileAccess implements MiddlewareInterface, ProfileAccessInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return UserEntity
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    protected function getUser(ServerRequestInterface $request): UserEntity
    {
        $repositoryManager = App::container()->get(RepositoryManager::class);

        /** @var RepositoryManager $repositoryManager */
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
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->allowed($request)) {
            return $handler->handle($request);
        }

        throw new UserNotAllowedException();
    }
}
