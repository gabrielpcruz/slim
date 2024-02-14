<?php

namespace App\Http\Api\Home;

use App\Repository\Example\RiceRespository;
use App\Service\Rice\RiceService;
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
        $riceRepository = new RiceService();

        $this->payloadResponse()->data = $riceRepository->all()->toArray();

        return $this->toJson($response);
    }
}
