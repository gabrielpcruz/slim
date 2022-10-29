<?php

namespace App\Http\Api;

use App\Business\Rice\RiceBusiness;
use App\Http\ApiController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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

        return $this->responseJSON(
            $response,
            $riceBusiness->all()->toArray()
        );
    }
}