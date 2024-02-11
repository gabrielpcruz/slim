<?php

namespace App\Http\Api\Home;

use App\Business\Rice\RiceBusiness;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Slim\Http\Api\ApiController;

class Home extends ApiController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        $riceBusiness = new RiceBusiness();

        $this->payloadResponse()->data = $riceBusiness->getRepository()->all()->toArray();

        return $this->toJson($response);
    }
}
