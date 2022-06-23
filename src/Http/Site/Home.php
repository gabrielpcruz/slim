<?php

namespace App\Http\Site;

use App\Http\ControllerSite;
use App\Repository\Example\RiceRespository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

class Home extends ControllerSite
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
        $repository = $this->getRepositoryManager()->get(RiceRespository::class);

        return $this->responseJSON(
            $response,
            $repository->all()->toArray()
        );
    }
}