<?php

/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Repository\User;

use App\Entity\User\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use App\Slim\Repository\AbstractRepository;

class ClientAbstractRepository extends AbstractRepository implements ClientRepositoryInterface
{
    public const string CLIENT_NAME = 'My Awesome App';
    public const string REDIRECT_URI = 'http://foo/bar';

    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier): ClientEntityInterface|ClientEntity|null
    {
        $client = new ClientEntity();

        $client->setIdentifier($clientIdentifier);
        $client->setName(self::CLIENT_NAME);
        $client->setRedirectUri(self::REDIRECT_URI);
        $client->setConfidential();

        /** @var ClientEntity $client */
        $client = $this->findOneBy(['identifier' => $clientIdentifier]);
        $client->setIdentifier($client->id);

        return $client;
    }

    /**
     * @param $clientIdentifier
     * @param $clientSecret
     * @param $grantType
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        return true;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getClientEntityByCredentials(array $data): mixed
    {
        $queryBuilder = $this->query();

        $queryBuilder->where('identifier', '=', $data['client_id']);

        return $queryBuilder->get()->first();
    }

    public function getEntityClass(): string
    {
        return ClientEntity::class;
    }
}
