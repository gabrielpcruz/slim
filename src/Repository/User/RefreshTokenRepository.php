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
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository extends Repository implements RefreshTokenRepositoryInterface
{
    use RefreshTokenTrait;

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
        $token = $this->findOneBy([
            'access_token' => $tokenId,
        ]);

        return !$token || ($token->isExpired());
    }

    /**
     * @return RefreshTokenEntity
     */
    public function getNewRefreshToken(): RefreshTokenEntity
    {
        return new RefreshTokenEntity();
    }
}
