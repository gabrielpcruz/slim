<?php

namespace App\Http\Site\Auth;

use App\Entity\User\UserEntity;
use App\Enum\FlashMessage;
use App\Message\Success\System\MessageSuccessSystem;
use App\Repository\User\UserRepository;
use App\Slim\Session\Session;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;
use App\Slim\Http\Site\SiteController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Login extends SiteController
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
        $data = (array) $request->getParsedBody();

        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepositoryManager()->get(UserRepository::class);

        /** @var UserEntity $user */
        $user = $userRepository->getUserEntityByCredentials($data);

        if ($user) {
            Session::user($user);

            $this->flash()->addMessage(FlashMessage::SUCCESS, MessageSuccessSystem::MSS0001);
        }

        return redirect('/logged');
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
