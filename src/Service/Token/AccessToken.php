<?php

namespace App\Service\Token;

use App\Entity\User\ClientEntity;
use App\Repository\User\AccessTokenAbstractRepository;
use App\Repository\User\ClientAbstractRepository;
use App\Repository\User\UserAbstractRepository;
use ReflectionException;
use App\Slim\Service\AbstractService;

class AccessToken extends AbstractService
{
    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return AccessTokenAbstractRepository::class;
    }

    /**
     * @param array $data
     *
     * @return ClientEntity|null
     * @throws ReflectionException
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
     */
    private function getClientByUserPassword(array $data): object
    {
        /** @var UserAbstractRepository $userRepository */
        $userRepository = $this->getRepository(UserAbstractRepository::class);

        $user = $userRepository->getUserEntityByCredentials($data);

        return $user->client()->first();
    }

    /**
     * @param array $data
     *
     * @return ClientEntity|null
     */
    private function getClientByIdentifier(array $data): ?ClientEntity
    {
        /** @var ClientAbstractRepository $clientRepository */
        $clientRepository = $this->getRepository(ClientAbstractRepository::class);

        return $clientRepository->getClientEntityByCredentials(
            $data
        );
    }
}
