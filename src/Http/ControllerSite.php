<?php

namespace App\Http;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Twig $twig, Request $request, Response $response)
    {
        parent::__construct($request, $response);
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