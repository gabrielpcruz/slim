<?php

namespace App\Http\Site;

use App\Business\Rice\RiceBusiness;
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
        $riceBusiness = new RiceBusiness();

        $rices = $riceBusiness->all()->toArray();

        return $this->view(
            $response,
            "@site/home/index",
            compact('rices')
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function logado(Request $request, Response $response): Response
    {
        $riceBusiness = new RiceBusiness();

        $rices = $riceBusiness->all()->toArray();

        return $this->view(
            $response,
            "@site/home/logado",
            compact('rices')
        );
    }
}