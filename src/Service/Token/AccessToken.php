<?php

namespace App\Service\Token;

use App\App;
use App\Repository\RepositoryManager;
use App\Repository\User\ClientRepository;
use App\Repository\User\UserRepository;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class AccessToken
{
    /**
     * @param $data
     *
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function getClientByGrant(array $data)
    {
        $grant_type = $data['grant_type'];

        switch ($grant_type) {
            case 'refresh_token':
                return $this->getTokenByClientIdentifier($data);
            default:
                return $this->getTokenByUserPassword($data);
        }
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
    private function getTokenByUserPassword(array $data)
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
    private function getTokenByClientIdentifier(array $data)
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = App::container()->get(RepositoryManager::class)->get(ClientRepository::class);

        return $clientRepository->getClientEntityByCredentials(
            $data
        );
    }
}
