<?php

namespace App\Provider;

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
        return 'asset';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', [$this, 'assets']),
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

        $asset = reset($params);

        return App::settings()->get('path.assets') . $asset;
    }
}