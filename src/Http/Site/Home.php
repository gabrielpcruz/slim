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
     */
    public function index(Request $request, Response $response): Response
    {
        $response->getBody()->write("Dentro do contoller, Método index");

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function arroz(Request $request, Response $response): Response
    {
        $response->getBody()->write("Dentro do contoller");

        return $response;
    }
}