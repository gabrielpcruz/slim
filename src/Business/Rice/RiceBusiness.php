<?php

namespace App\Business\Rice;

use App\Business\Business;
use App\Entity\Entity;
use App\Repository\Example\RiceRespository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Psr\Http\Message\ServerRequestInterface as Request;

class RiceBusiness extends Business
{
    /**
     * @var string
     */
    protected string $repositoryClass = RiceRespository::class;

    /**
     * @return Builder[]|Collection
     */
    public function all(Request $request): Collection
    {
        $query = $this->getRepository()->query();

        $query->whereNull(['deleted_at']);

        return $query->get();
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function save(Entity $entity): void
    {
        $entity->save();
    }
}
