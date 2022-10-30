<?php

namespace App\Service;

use App\App;
use App\Repository\RepositoryManager;
use App\Repository\User\ClientRepository;
use App\Repository\User\UserRepository;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class AccessTokenService
{
    /**
     * @param $data
     *
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function getClientByGrant($data)
    {
        $grant_type = $data['grant_type'];

        switch ($grant_type) {
            case 'client_credentials':
                return $this->getTokenByClientCredentials($data);
            case 'password':
                return $this->getTokenByUserPassword($data);
            case 'refresh_token':
                return $this->getTokenByClientIdentifier($data);
        }
    }

    /**
     * @param $data
     *
     * @return mixed
     * @throws ReflectionException
     *
     */
    private function getTokenByClientCredentials($data)
    {
        return $this->clientService->getRepository()->findBy(
            [
                'identifier' => $data['client_id'],
                'secret' => $data['client_secret'],
            ]
        )->first();
    }

    /**
     * @param $data
     *
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getTokenByUserPassword($data)
    {
        $repositoryManager = App::container()->get(RepositoryManager::class);

        $user = $repositoryManager->get(UserRepository::class)->getUserEntityByCredentials(
            $data
        );

        return $user->client()->first();
    }

    /**
     * @param $data
     *
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getTokenByClientIdentifier($data)
    {
        $repositoryManager = App::container()->get(RepositoryManager::class);

        return $repositoryManager->get(ClientRepository::class)->getClientEntityByCredentials(
            $data
        );
    }
}