<?php

namespace App\Middleware\Authentication;

use App\Repository\RepositoryManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Authentication implements MiddlewareInterface, AuthenticationInterface
{
    /**
     * @var RepositoryManager
     */
    protected RepositoryManager $repositoryManager;

    /**
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->authenticate($request);

        return $handler->handle($request);
    }
}