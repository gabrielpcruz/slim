<?php

namespace App\Middleware\Authentication;

use App\App;
use App\Exception\UserNotAllowedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationSite implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     * @throws UserNotAllowedException
     */
    public function authenticate(ServerRequestInterface $request)
    {


        return $request;
//        $oauth2PublicKey = App::settings()->get('file.oauth_public');
//
//        /** @var AccessTokenRepository $accessTokenRepository */
//        $accessTokenRepository = $this->repositoryManager->get(AccessTokenRepository::class);
//
//        $server = new ResourceServer(
//            $accessTokenRepository,
//            $oauth2PublicKey
//        );
//
//        $request = $server->validateAuthenticatedRequest($request);
//        $clientRepository = $this->repositoryManager->get(ClientRepository::class);
//
//        $client = $clientRepository->findOneBy([
//            'id' => $request->getAttribute('oauth_client_id'),
//        ]);
//
//        if (!$client) {
//            throw new HttpUnauthorizedException($request);
//        }
//
//        return $request->withAttribute('oauth_client_id', $client->id);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws UserNotAllowedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            return App::getInstace()->redirect('/', '/login');
        }

        return $handler->handle($request);
    }
}