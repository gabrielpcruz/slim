<?php

namespace App\Http\Api;

use App\Business\Rice\RiceBusiness;
use App\Http\ControllerApi;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

class Home extends ControllerApi
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function index(Request $request, Response $response): Response
    {
        $riceBusiness = new RiceBusiness($this->container);

        return $this->responseJSON(
            $response,
            $riceBusiness->all()->toArray()
        );
    }
}