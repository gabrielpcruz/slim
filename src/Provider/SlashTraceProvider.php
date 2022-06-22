<?php

namespace App\Provider;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SlashTrace\EventHandler\DebugHandler;
use SlashTrace\SlashTrace;

class SlashTraceProvider implements ProviderInterface
{
    /**
     * @param ContainerInterface $container
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function provid(ContainerInterface $container)
    {
        if ($container->get('settings')->get('error.slashtrace')) {
            $slashtrace = new SlashTrace();

            $slashtrace->addHandler(new DebugHandler());

            $slashtrace->register();
        }
    }
}