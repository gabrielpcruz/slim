<?php

namespace App\Factory;

use Psr\Container\ContainerInterface;
use SlashTrace\EventHandler\DebugHandler;
use SlashTrace\SlashTrace;

class SlachTraceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @return SlashTrace
     */
    public function create(ContainerInterface $container): SlashTrace
    {
        $slashtrace = new SlashTrace();

        $slashtrace->addHandler(new DebugHandler());

        $slashtrace->register();

        return $slashtrace;
    }
}