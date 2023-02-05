<?php

namespace App\Twig;

use App\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IsProductionTwigExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'is_production';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_production', [$this, 'is_production']),
        ];
    }

    /**
     * @return bool
     */
    public function is_production(): bool
    {
        return App::isProduction();
    }
}
