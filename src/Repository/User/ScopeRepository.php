<?php

/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Repository\User;

use App\Entity\User\ScopeEntity;
use App\Repository\Repository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

use function array_key_exists;

class ScopeRepository extends Repository implements ScopeRepositoryInterface
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return ScopeEntity::class;
    }

    /**
     * @param $identifier
     * @return ScopeEntity|void
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        $scopes = [
            'basic' => [
                'description' => 'Basic details about you',
            ],
            'email' => [
                'description' => 'Your email address',
            ],
        ];
        if (array_key_exists($identifier, $scopes) === false) {
            return;
        }

        $scope = new ScopeEntity();
        $scope->setIdentifier($identifier);
        return $scope;
    }

    /**
     * @param array $scopes
     * @param $grantType
     * @param ClientEntityInterface $clientEntity
     * @param $userIdentifier
     * @return array|ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ): array {
        // Example of programatically modifying the final scope of the access token
        if ((int)$userIdentifier === 1) {
            $scope = new ScopeEntity();
            $scope->setIdentifier('email');
            $scopes[] = $scope;
        }

        return $scopes;
    }
}
