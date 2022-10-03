<?php

namespace App\Handler;

use App\App;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Views\Twig;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Default Slim application error handler
 *
 * It outputs the error message and diagnostic information in one of the following formats:
 * JSON, XML, Plain Text or HTML based on the Accept header.
 */
class DefaultErrorHandler implements ErrorHandlerInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws SyntaxError
     * @throws ContainerExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable              $exception,
        bool                   $displayErrorDetails,
        bool                   $logErrors,
        bool                   $logErrorDetails
    ): ResponseInterface
    {
        /** @var Twig $view */
        $view = $this->container->get(Twig::class);

        $response = new Response();

        $message = $exception->getMessage();
        $code = ($exception->getCode() > 99 && $exception->getCode() < 600) ? intval($exception->getCode()) : 500;


        if ($this->isApi($request)) {
            return $this->respondeApi($message, $code, $response);
        }

        $pathTemplate = App::settings()->get('view.templates.error');

        $exists = file_exists("$pathTemplate/$code/index.twig");

        $template = $exists ? "@error/$code/index.twig" : "@error/index.twig";

        $response = $response->withStatus($code);

        return $view->render(
            $response,
            $template,
            compact('message', 'code')
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    private function isApi(ServerRequestInterface $request): bool
    {
        $accept = explode(',', $request->getHeader('Accept')[0]);

        return !in_array('text/html', $accept);
    }

    /**
     * @param string $message
     * @param int $code
     * @param Response $response
     * @return mixed
     */
    private function respondeApi(string $message, int $code, Response $response)
    {
        $responseCode = $code < 300 ? 500 : $code;

        $json = json_encode([
            'message' => $message,
            'code' => $responseCode
        ], JSON_PRETTY_PRINT);

        $response->getBody()->write($json);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($responseCode);
    }
}
