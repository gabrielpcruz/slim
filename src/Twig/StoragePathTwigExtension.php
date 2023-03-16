<?php

namespace App\Twig;

use App\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StoragePathTwigExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'storage_path';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('storage_path', [$this, 'storage_path']),
        ];
    }

    /**
     * @return string
     */
    public function storage_path(): string
    {
        $host = $_SERVER['HTTP_HOST'];

        $protocol = App::isProduction() ? 'https' : 'http';

        return "{$protocol}://{$host}/storage";
    }
}
