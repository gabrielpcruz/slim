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
        $protocolo = $_SERVER['REQUEST_SCHEME'];
        $host = $_SERVER['HTTP_HOST'];

        $ssl = App::isProduction() ? 's' : '';

        return "{$protocolo}{$ssl}://{$host}/storage";
    }
}
