<?php

/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Entity\User;

use App\Slim\Entity\Entity;

class ProfileEntity extends Entity
{
    /**
     * @var string
     */
    protected $table = 'profile';
}
