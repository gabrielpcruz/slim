<?php

namespace App\Service\Rice;

use App\Repository\Example\RiceRespository;
use App\Service\Service;
use Illuminate\Database\Eloquent\Collection;

class RiceService extends Service
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
