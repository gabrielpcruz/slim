<?php

namespace App\Http;

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
    private Twig $twig;

    /**
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
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