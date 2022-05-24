<?php

namespace App\Factory;

use Psr\Container\ContainerInterface;

interface FactoryInterface
{
    public function create(ContainerInterface $container);
}