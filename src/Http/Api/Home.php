<?php

namespace App\Http\Api;

use App\Http\ControllerApi;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Home extends ControllerApi
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        return $this->responseJSON($response, [
            'message' => 'response message'
        ]);
    }
}