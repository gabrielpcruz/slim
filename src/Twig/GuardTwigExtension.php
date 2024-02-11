<?php

namespace App\Twig;

use App\Slim\Session\Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GuardTwigExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'guard';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('guard', [$this, 'guard']),
        ];
    }

    /**
     * @return bool
     */
    public function guard(): bool
    {
        return true === Session::isLoggedIn();
    }
}
