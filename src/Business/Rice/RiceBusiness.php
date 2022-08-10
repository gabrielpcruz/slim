<?php

namespace App\Business\Rice;

use App\Business\Business;
use App\Repository\Example\RiceRespository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RiceBusiness extends Business
{
    /**
     * @var string
     */
    protected string $repositoryClass = RiceRespository::class;

    /**
     * @return Builder[]|Collection
     */
    public function all(): Collection
    {
        $query = $this->getRepository()->query();

        $query->whereNull(['deleted_at']);

        return $query->get();
    }
}