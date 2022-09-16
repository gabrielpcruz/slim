<?php

namespace App\Middleware\ProfileAccess;

use App\App;
use App\Entity\User\UserEntity;
use App\Repository\RepositoryManager;
use App\Repository\User\UserRepository;
use DI\DependencyException;
use DI\NotFoundException;
use App\Enum\EnumProfile;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;

class Admin extends ProfileAccess
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function allowed(ServerRequestInterface $request): bool
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

        return EnumProfile::isAdmin($user->profile()->first()->name);
    }
}