<?php

namespace App\Slim\Handler;

use App\App;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
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
class ErrorHandler implements ErrorHandlerInterface
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
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        /** @var Twig $view */
        $view = $this->container->get(Twig::class);

        $response = new Response();
        $message = "";

        $messageTemplate = "
            <strong>Message</strong>: %s <br/>
            <strong>File</strong>: %s <br/>
            <strong>Line</strong>: %s <br/>
            <strong>Stacktrace</strong>: %s
        ";

        if (!App::isProduction()) {
            $message = $exception->getMessage();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $stacktrace = $exception->getTraceAsString();
            $breakLine = PHP_EOL;

            $message = sprintf(
                $messageTemplate,
                $message . str_repeat($breakLine, 1),
                $file . str_repeat($breakLine, 1),
                $line . str_repeat($breakLine, 1),
                $stacktrace . str_repeat($breakLine, 1),
            );
        }

        $code = ($exception->getCode() > 99 && $exception->getCode() < 600) ? $exception->getCode() : 500;

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
}
