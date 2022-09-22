<?php

namespace App\Http\Site;

use App\Business\Rice\RiceBusiness;
use App\Http\ControllerSite;
use App\Repository\User\UserRepository;
use App\Service\Session;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

class Login extends ControllerSite
{
    public function index(Request $request, Response $response): Response
    {
        return $this->view(
            $response,
            "@site/login/index",
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepositoryManager()->get(UserRepository::class);

        $user = $userRepository->getUserEntityByCredentials($data);

        if ($user) {
            Session::sessionStart($user);
        }

        return redirect('/logado');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function logout(Request $request, Response $response): Response
    {
        Session::logout();

        return redirect('/login');
    }
}