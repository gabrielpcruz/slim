<?php

namespace App\Repository\Example;

use App\Entity\Example\RiceEntity;
use App\Slim\Repository\AbstractRepository;

class RiceRespository extends AbstractRepository
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return RiceEntity::class;
    }
}
