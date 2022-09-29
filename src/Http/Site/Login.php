<?php

namespace App\Http\Site;

use App\Enum\FlashMessage;
use App\Http\ControllerSite;
use App\Message\Success\System\MessageSuccessSystem;
use App\Repository\User\UserRepository;
use App\Service\Session;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Login extends ControllerSite
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
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
            Session::user($user);

            $this->flash()->addMedssage(FlashMessage::SUCCESS, MessageSuccessSystem::MSS0001);
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