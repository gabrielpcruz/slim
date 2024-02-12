<?php

/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Entity\User;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;
use App\Slim\Entity\Entity;

class ScopeEntity extends Entity implements ScopeEntityInterface
{
    use EntityTrait;
    use ScopeTrait;

    /**
     * @var string
     */
    protected $table = 'oauth2_scope';
}
