<?php

namespace App\Middleware\Api\Authentication\ProfileAccess;

use App\Enum\EnumProfile;
use DI\DependencyException;
use DI\NotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;

class Administrator extends ProfileAccess
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException|OAuthServerException
     */
    public function allowed(ServerRequestInterface $request): bool
    {
        $user = $this->getUser($request);

        return EnumProfile::isAdmin($user->profile()->first()->name);
    }
}
