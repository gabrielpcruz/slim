<?php

namespace App\Service\Token;

use App\App;
use App\Entity\User\ClientEntity;
use App\Entity\User\UserEntity;
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
     * @param array $data
     *
     * @return ClientEntity|null
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function getClientByGrant(array $data): ?ClientEntity
    {
        $grant_type = $data['grant_type'];

        switch ($grant_type) {
            case 'refresh_token':
                return $this->getClientByIdentifier($data);
            default:
                return $this->getClientByUserPassword($data);
        }
    }

    /**
     * @param array $data
     *
     * @return ClientEntity|null
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    private function getClientByUserPassword(array $data): ?ClientEntity
    {
        $repositoryManager = App::container()->get(RepositoryManager::class);

        $user = $repositoryManager->get(UserRepository::class)->getUserEntityByCredentials(
            $data
        );

        return $user->client()->first();
    }

    /**
     * @param array $data
     *
     * @return ClientEntity|null
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    private function getClientByIdentifier(array $data): ?ClientEntity
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = App::container()->get(RepositoryManager::class)->get(ClientRepository::class);

        return $clientRepository->getClientEntityByCredentials(
            $data
        );
    }
}
