<?php

namespace App\Http\Site\Auth;

use App\Entity\User\UserEntity;
use App\Message\Success\System\MessageSuccessSystem;
use App\Repository\User\UserAbstractRepository;
use App\Slim\Http\Site\SiteAbstractController;
use App\Slim\Session\Session;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\Slim\Enum\FlashMessage;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Login extends SiteAbstractController
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
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function login(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();

        /** @var UserAbstractRepository $userRepository */
        $userRepository = $this->getRepositoryManager()->get(UserAbstractRepository::class);

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
