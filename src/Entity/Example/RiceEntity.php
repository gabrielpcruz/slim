<?php

namespace App\Entity\Example;

use App\Entity\Entity;

class RiceEntity extends Entity
{
    protected $table = 'rice';

    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $name;
}
