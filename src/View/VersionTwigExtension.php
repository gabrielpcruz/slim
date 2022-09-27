<?php

namespace App\View;

use App\App;
use App\Service\Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VersionTwigExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'version';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('version', [$this, 'version']),
        ];
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return App::version();
    }
}