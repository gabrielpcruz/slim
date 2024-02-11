<?php

namespace App\Service\Token;

use App\App;
use App\Entity\User\ClientEntity;
use App\Repository\User\ClientRepository;
use App\Repository\User\UserRepository;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use App\Slim\Repository\RepositoryManager;

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

        return match ($grant_type) {
            'refresh_token' => $this->getClientByIdentifier($data),
            default => $this->getClientByUserPassword($data),
        };
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
