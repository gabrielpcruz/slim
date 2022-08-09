<?php

namespace App\Http\Site;

use App\App;
use App\Http\ControllerSite;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Yaml\Yaml;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Documentation extends ControllerSite
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request, Response $response): Response
    {
        $apiPath = App::getInstace()->getContainer()->get('settings')->get('view.path');
        $yamlFile = $apiPath . '/api/documentation.yaml';

        return $this->view(
            $response,
            '@api/swagger',
            [
                'template' => json_encode(Yaml::parseFile($yamlFile)),
                'arroz' => 'arroz',
            ]
        );
    }
}