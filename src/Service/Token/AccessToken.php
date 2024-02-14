<?php

namespace App\Service\Token;

use App\App;
use App\Entity\User\ClientEntity;
use App\Repository\User\AccessTokenRepository;
use App\Repository\User\ClientRepository;
use App\Repository\User\UserRepository;
use App\Service\Service;
use DI\DependencyException;
use DI\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use App\Slim\Repository\RepositoryManager;
use ReflectionException;

class AccessToken extends Service
{
    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return AccessTokenRepository::class;
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
     * @return object
     * @throws ReflectionException
     */
    private function getClientByUserPassword(array $data): object
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepository(UserRepository::class);

        $user = $userRepository->getUserEntityByCredentials($data);

        return $user->client()->first();
    }

    /**
     * @param array $data
     *
     * @return ClientEntity|null
     * @throws ReflectionException
     */
    private function getClientByIdentifier(array $data): ?ClientEntity
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = $this->getRepository(ClientRepository::class);

        return $clientRepository->getClientEntityByCredentials(
            $data
        );
    }
}
