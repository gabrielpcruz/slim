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
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AccessTokenEntity extends Entity implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use TokenEntityTrait;
    use EntityTrait;

    /**
     * @var string
     */
    protected $table = 'oauth2_access_token';

    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $user_id;

    /**
     * @var string
     */
    public string $oauth2_client_id;

    /**
     * @var string
     */
    public string $access_token;

    /**
     * @var DateTimeImmutable
     */
    public DateTimeImmutable $expiry_date_time;

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
