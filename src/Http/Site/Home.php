<?php

namespace App\Http\Site;

use App\Http\ControllerSite;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Home extends ControllerSite
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        return $this->view(
            $response,
            '@site/home/index',
            [
                'data' => date('H:i')
            ]
        );
    }
}