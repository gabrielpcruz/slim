<?php

namespace App\Twig;

use App\Service\Session\Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GuestTwigExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'guest';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('guest', [$this, 'guest']),
        ];
    }

    /**
     * @return bool
     */
    public function guest(): bool
    {
        return false == Session::isLoggedIn();
    }
}
