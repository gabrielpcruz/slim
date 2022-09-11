<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Repository\User;

use App\Entity\User\RefreshTokenEntity;
use App\Repository\Repository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository extends Repository implements RefreshTokenRepositoryInterface
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return RefreshTokenEntity::class;
    }

    /**
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     * @return void
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        // Some logic to persist the refresh token in a database
    }

    /**
     * @param $tokenId
     * @return void
     */
    public function revokeRefreshToken($tokenId)
    {
        // Some logic to revoke the refresh token in a database
    }

    /**
     * @param $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        return false; // The refresh token has not been revoked
    }

    /**
     * @return RefreshTokenEntity
     */
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity();
    }
}
