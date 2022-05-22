<?php

namespace App\Http;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Controller
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function __invoke(
        Request  $request,
        Response $response,
        array    $args
    ): Response
    {
        var_dump('$controller');
        var_dump('$method');

        $method = $this->getMethodController($request);
        $httpVerb = $request->getMethod();

        $controller = $this->getCompletePathController($request);


    }

    /**
     * @param Request $request
     * @return string|void
     */
    private function getMethodController(Request $request)
    {
        $path = $request->getUri()->getPath();

        if ($path === '/') {
            return 'index';
        }

        var_dump($path);
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getCompletePathController(Request $request): string
    {
        return $request->getAttribute('__route__')->getCallable();
    }

    /**
     * @param $template
     * @param array $args
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view($template, array $args = []): string
    {
        /** @var Environment $view */
        $view = $this->getContainer()->get('twig');
        return $view->render($template, $args);
    }
}