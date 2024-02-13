<?php

namespace App\Http\Api\Home;

use App\Repository\Example\RiceRespository;
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
        $riceRepository = new RiceRespository();

        $this->payloadResponse()->data = $riceRepository->all()->toArray();

        return $this->toJson($response);
    }
}
