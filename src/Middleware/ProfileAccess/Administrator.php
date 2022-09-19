<?php

namespace App\Middleware\ProfileAccess;

use DI\DependencyException;
use DI\NotFoundException;
use App\Enum\EnumProfile;
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
     * @throws ReflectionException
     */
    public function allowed(ServerRequestInterface $request): bool
    {
        $user = $this->getUser($request);

        return EnumProfile::isAdmin($user->profile()->first()->name);
    }
}