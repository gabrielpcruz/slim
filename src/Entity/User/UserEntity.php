<?php

namespace App\Entity\User;

use App\Entity\Entity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity extends Entity implements UserEntityInterface
{
    protected $table = 'user';

    public function getIdentifier(): int
    {
        return 1;
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientEntity::class);
    }
}