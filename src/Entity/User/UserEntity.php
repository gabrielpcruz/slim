<?php

namespace App\Entity\User;

use App\Entity\Entity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity extends Entity implements UserEntityInterface
{
    protected $table = 'user';

    /**
     * @var int
     */
    public int $id;

    /**
     * @return int
     */
    public function getIdentifier(): int
    {
        return $this->id;
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientEntity::class);
    }

    /**
     * @return BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(ProfileEntity::class);
    }

    /**
     * @return BelongsTo
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(AccessTokenEntity::class);
    }
}
