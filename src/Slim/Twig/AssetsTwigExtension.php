<?php

namespace App\Slim\Twig;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetsTwigExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'assets';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('assets', [$this, 'assets']),
        ];
    }

    /**
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function assets(): string
    {
        $params = func_get_args();

        $protocolo = $_SERVER['REQUEST_SCHEME'];
        $host = $_SERVER['HTTP_HOST'];

        $asset = reset($params);

        $ssl = App::isProduction() ? 's' : '';

        return "{$protocolo}{$ssl}://{$host}/" . App::settings()->get('path.assets') . $asset;
    }
}
