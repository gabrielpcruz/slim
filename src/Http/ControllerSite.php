<?php

namespace App\Http;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ControllerSite extends Controller
{
    /**
     * @var Twig
     */
    protected Twig $twig;

    /**
     * @param ContainerInterface $container
     * @param Twig $twig
     */
    public function __construct(ContainerInterface $container, Twig $twig)
    {
        parent::__construct($container);
        $this->twig = $twig;
    }

    /**
     * @param Response $response
     * @param $template
     * @param array $args
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view(
        Response $response,
                 $template,
        array    $args = []
    ): Response
    {
        return $this->twig->render($response, $template . ".twig", $args);
    }
}