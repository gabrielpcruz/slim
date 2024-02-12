<?php

/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Entity\User;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use App\Slim\Entity\Entity;

class ClientEntity extends Entity implements ClientEntityInterface
{
    use EntityTrait;
    use ClientTrait;

    /**
     * @var string
     */
    protected $table = 'oauth2_client';

    /**
     * @var int
     */
    public int $id;

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setRedirectUri($uri): void
    {
        $this->redirectUri = $uri;
    }

    public function setConfidential()
    {
        $this->isConfidential = true;
    }
}
