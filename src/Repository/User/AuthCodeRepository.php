<?php

/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Repository\User;

use App\Entity\User\AuthCodeEntity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * @param AuthCodeEntityInterface $authCodeEntity
     * @return void
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity) : void
    {
        // Some logic to persist the auth code to a database
    }

    /**
     * @param $codeId
     * @return void
     */
    public function revokeAuthCode($codeId) : void
    {
        // Some logic to revoke the auth code in a database
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId): bool
    {
        // The auth code has not been revoked
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode(): AuthCodeEntityInterface|AuthCodeEntity
    {
        return new AuthCodeEntity();
    }
}
