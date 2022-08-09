<?php

namespace App\Repository\Example;

use App\Entity\Example\RiceEntity;
use App\Repository\Repository;

class RiceRespository extends Repository
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return RiceEntity::class;
    }
}