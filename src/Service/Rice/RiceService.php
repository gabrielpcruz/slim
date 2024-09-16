<?php

namespace App\Service\Rice;

use App\Repository\Example\RiceRespository;
use Illuminate\Database\Eloquent\Collection;
use App\Slim\Service\AbstractService;

class RiceService extends AbstractService
{
    /**
     * @return string
     */
    #[\Override] protected function getRepositoryClass(): string
    {
        return RiceRespository::class;
    }

    /**
     * @return Collection|array
     */
    public function all(): Collection|array
    {
        return $this->getRepository()->all();
    }
}
