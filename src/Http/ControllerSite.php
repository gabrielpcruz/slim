<?php

namespace App\Http;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ControllerSite extends Controller
{
    /**
     * @param ResponseInterface $response
     * @param $template
     * @param array $args
     * @return ResponseInterface
     * @throws DependencyException
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view(
        ResponseInterface $response,
                          $template,
        array             $args = []
    ): ResponseInterface
    {
        /** @var Twig $view */
        $view = App::getContainer()->get(Twig::class);

        return $view->render($response, $template . ".twig", $args);
    }
}