<?php

namespace App\Business\Rice;

use App\Repository\Example\RiceRespository;
use App\Slim\Business\Business;
use Illuminate\Database\Eloquent\Collection;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\Slim\Entity\Entity;

class RiceBusiness extends Business
{
    /**
     * @var string
     */
    protected string $repositoryClass = RiceRespository::class;

    /**
     * @param Request $request
     * @return Collection
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
