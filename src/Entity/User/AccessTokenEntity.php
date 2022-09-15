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
use Illuminate\Database\Eloquent\Relations\HasOne;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AccessTokenEntity extends Entity implements AccessTokenEntityInterface
{
    use AccessTokenTrait, TokenEntityTrait, EntityTrait;

    /**
     * @var string
     */
    protected $table = 'oauth2_access_token';

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        $tokenExpiration = DateTime::createFromFormat('Y-m-d H:i:s', $this->expiry_date_time);

        return $tokenExpiration->getTimestamp() <= (new DateTime())->getTimestamp();
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(UserEntity::class);
    }
}
