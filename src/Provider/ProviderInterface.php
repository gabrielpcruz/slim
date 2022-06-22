<?php

namespace App\Provider;

use Psr\Container\ContainerInterface;

interface ProviderInterface
{
    public function provid(ContainerInterface $container);
}