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
use ReflectionException;

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
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        /** @var AccessTokenEntity $accessTokenEntity */
        $accessTokenEntity->access_token = $accessTokenEntity->getIdentifier();
        $accessTokenEntity->expiry_date_time = $accessTokenEntity->getExpiryDateTime();

        if ($accessTokenEntity->getUserIdentifier()) {
            $accessTokenEntity->user_id = $accessTokenEntity->getUserIdentifier();
        }

        $accessTokenEntity->oauth2_client_id = $accessTokenEntity->getClient()->getIdentifier();
        $this->save($accessTokenEntity);
    }

    /**
     * @param $tokenId
     * @return void
     * @throws ReflectionException
     */
    public function revokeAccessToken($tokenId): void
    {
        if ($this->isAccessTokenRevoked($tokenId)) {
            $token = $this->findOneBy([
                'access_token' => $tokenId,
            ]);

            $refreshTokenRepository = $this->getRepositoryManager()->get(RefreshTokenRepository::class);

            $refreshToken = $refreshTokenRepository->findOneBy([
                'oauth2_access_token_id' => $token->getAttribute('id')
            ]);

            $refreshToken->delete();
            $token->delete();
        }
    }

    /**
     * @param $tokenId
     * @return bool
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        /** @var AccessTokenEntity $token */
        $token = $this->findOneBy([
            'access_token' => $tokenId,
        ]);

        return !$token || ($token->isExpired());
    }

    /**
     * @param ClientEntityInterface $clientEntity
     * @param array $scopes
     * @param $userIdentifier
     * @return AccessTokenEntity
     */
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ): AccessTokenEntity {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }
}
