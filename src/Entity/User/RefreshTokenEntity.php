<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Entity\User;

use App\Entity\Entity;
use DateTime;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

class RefreshTokenEntity extends Entity implements RefreshTokenEntityInterface
{
    use RefreshTokenTrait, EntityTrait;

    /**
     * @var string
     */
    protected $table = 'oauth2_refresh_token';

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        $tokenExpiration = DateTime::createFromFormat('Y-m-d H:i:s', $this->expire_time);

        return $tokenExpiration->getTimestamp() <= (new DateTime())->getTimestamp();
    }
}
