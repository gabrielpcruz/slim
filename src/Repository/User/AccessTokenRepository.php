<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Repository\User;

use App\Entity\User\AccessTokenEntity;
use App\Repository\Repository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository extends Repository implements AccessTokenRepositoryInterface
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return AccessTokenEntity::class;
    }

    /**
     * @param AccessTokenEntityInterface $accessTokenEntity
     * @return void
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        // Some logic here to save the access token to a database
    }

    /**
     * @param $tokenId
     * @return void
     */
    public function revokeAccessToken($tokenId)
    {
        // Some logic here to revoke the access token
    }

    /**
     * @param $tokenId
     * @return bool
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        return false; // Access token hasn't been revoked
    }

    /**
     * @param ClientEntityInterface $clientEntity
     * @param array $scopes
     * @param $userIdentifier
     * @return AccessTokenEntity
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }
}
