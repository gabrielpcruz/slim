<?php

namespace App\Slim\Provider;

use Adbar\Dot;
use Psr\Container\ContainerInterface;
use SlashTrace\EventHandler\DebugHandler;
use SlashTrace\SlashTrace;

class SlashTraceProvider implements ProviderInterface
{
    /**
     * @param ContainerInterface $container
     * @param Dot $settings
     * @return void
     */
    public function provide(ContainerInterface $container, Dot $settings): void
    {
        if ($settings->get('error.slashtrace')) {
            $slashtrace = new SlashTrace();

            $slashtrace->addHandler(new DebugHandler());

            $slashtrace->register();
        }
    }
}
