<?php

namespace App\Provider;

use Psr\Container\ContainerInterface;

interface ProviderInterface
{
    public function provide(ContainerInterface $container);
}