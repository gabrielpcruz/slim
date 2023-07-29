<?php

namespace App\Seeder;

use App\App;
use App\Seeder\Slim\Seeder;
use DateTime;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RiceSeeder extends Seeder
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     */
    public function run(): void
    {
        for ($i = 1; $i < 100; $i++) {
            $id = uniqid();

            $this->connection()->table('rice')->insert([
                'name' => "rice_{$id}",
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]);
        }
    }
}
