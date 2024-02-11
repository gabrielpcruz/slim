<?php

namespace App\Slim\Twig;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Slim\Flash\Messages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashMessageTwigExtension extends AbstractExtension
{
    /**
     * @var Messages
     */
    protected Messages $flash;

    /**
     * Constructor.
     *
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct()
    {
        $this->flash = App::flash();
    }

    /**
     * Extension name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'flash';
    }

    /**
     * Callback for twig.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'getMessages']),
        ];
    }

    /**
     * Returns Flash messages; If key is provided then returns messages
     * for that key.
     *
     * @param string|null $key
     *
     * @return array|null
     */
    public function getMessages(string $key = null): ?array
    {
        if (null !== $key) {
            return $this->flash->getMessage($key);
        }

        return $this->flash->getMessages();
    }
}
