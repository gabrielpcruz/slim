<?php

namespace App\Http\Site;

use App\Http\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Home extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $response->getBody()->write("Dentro do contoller, Método get");

        return $this->view('index');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function arroz(Request $request, Response $response): Response
    {
        $response->getBody()->write("Dentro do contoller, Método POST");

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function terra(Request $request, Response $response): Response
    {
        $response->getBody()->write("/arroz GET 2");

        return $response;
    }
}