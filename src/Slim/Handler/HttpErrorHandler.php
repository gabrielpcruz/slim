<?php

namespace App\Slim\Handler;

use App\Exception\Api\Authentication\GrantTypeMissingException;
use App\Exception\Entity\InvalidEntityParametersCreation;
use App\Exception\Entity\InvalidEntityParametersUpdate;
use App\Message\Code;
use App\Message\Message;
use App\Slim\Exception\UserNotAllowedException;
use App\Slim\Utils\Dynamic;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
use Throwable;

class HttpErrorHandler extends ErrorHandler
{
    public const string BAD_REQUEST = 'BAD_REQUEST';
    public const string UNAUTHORIZED = 'UNAUTHORIZED';
    public const string INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const string NOT_ALLOWED = 'NOT_ALLOWED';
    public const string NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    public const string RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const string SERVER_ERROR = 'SERVER_ERROR';
    public const string UNAUTHENTICATED = 'UNAUTHENTICATED';
    public const string REGISTRY_DUPLICATED = 'REGISTRY_DUPLICATED';

    /**
     * @param Dynamic $errorStruct
     * @return ResponseInterface
     */
    public function finalResponse(Dynamic $errorStruct): ResponseInterface
    {
        $error = $this->getErrorStruct($errorStruct);

        return $this->responseJson($error, $errorStruct->statusCode);
    }

    /**
     * @param Dynamic $errorStruct
     * @return array
     */
    public function getErrorStruct(Dynamic $errorStruct): array
    {
        return [
            'statusCode' => $errorStruct->statusCode,
            'error' => [
                'type' => $errorStruct->type,
                'description' => $errorStruct->description,
                'iternal_code' => $errorStruct->internalCode,
            ],
        ];
    }

    /**
     * @param array $error
     * @param int $statusCode
     * @return ResponseInterface
     */
    public function responseJson(array $error, int $statusCode): ResponseInterface
    {
        $payload = json_encode($error, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($payload);

        return $response;
    }

    /**
     * @var array|string[]
     */
    protected array $authenticationException = [
        LogicException::class,
        OAuthServerException::class,
        GrantTypeMissingException::class,
        UserNotAllowedException::class
    ];

    /**
     * @var array|string[]
     */
    protected array $entityException = [
        EntityNotFoundException::class,
        InvalidEntityParametersCreation::class,
        InvalidEntityParametersUpdate::class,
    ];

    /**
     * @param string $exceptionClass
     * @return bool
     */
    private function authenticationException(string $exceptionClass): bool
    {
        return in_array($exceptionClass, $this->authenticationException);
    }

    /**
     * @return ResponseInterface
     */
    protected function respond(): ResponseInterface
    {
        $errorStruct = new Dynamic();

        $exception = $this->exception;

        $errorStruct->statusCode = 500;
        $errorStruct->type = self::SERVER_ERROR;
        $errorStruct->internalCode = '099999';
        $errorStruct->description = 'Um erro interno ocorreu durante o processamento da sua requisção.';

        if ($this->authenticationException(get_class($exception))) {
            $errorStruct = $this->authenticationHandler($exception);

            return $this->finalResponse($errorStruct);
        }

        if ($this->entityException(get_class($exception))) {
            $errorStruct = $this->entityHandler($exception);

            return $this->finalResponse($errorStruct);
        }

        if ($exception instanceof HttpException) {
            $errorStruct->statusCode = $exception->getCode();
            $errorStruct->description = $exception->getMessage();

            if ($exception instanceof HttpNotFoundException) {
                $errorStruct->type = self::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $errorStruct->type = self::NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $errorStruct->type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $errorStruct->type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpBadRequestException) {
                $errorStruct->type = self::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $errorStruct->type = self::NOT_IMPLEMENTED;
            }
        }

        return $this->finalResponse($errorStruct);
    }

    /**
     * @param Throwable $exception
     * @return Dynamic
     */
    public function authenticationHandler(Throwable $exception): Dynamic
    {
        $errorStruct = new Dynamic();

        $statusCode = 500;
        $type = self::SERVER_ERROR;
        $description = Message::MS099999;
        $internalCode = Code::MS099999;

        if ($exception->getMessage() === 'Invalid key supplied') {
            $internalCode = Code::MS000003;
            $description = "Erro interno. Comunique ao administrador do sistema o código {$internalCode}";
        }

        if ($exception instanceof OAuthServerException) {
            $description = $exception->getHint() ?? $exception->getMessage();
            $statusCode = 401;
            $type = self::UNAUTHORIZED;

            if ($description == 'Missing "Authorization" header') {
                $internalCode = Code::MS000004;
            }
        }

        if ($exception instanceof GrantTypeMissingException) {
            $description = 'Você não informou o parâmetro grant type';
            $statusCode = 400;
            $type = self::BAD_REQUEST;
            $internalCode = Code::MS000005;
        }

        if ($exception instanceof UserNotAllowedException) {
            $description = 'Você não possui permissão para acessar o recurso';
            $statusCode = 401;
            $type = self::UNAUTHORIZED;
            $internalCode = Code::MS000006;
        }

        $errorStruct->statusCode = $statusCode;
        $errorStruct->description = $description;
        $errorStruct->type = $type;
        $errorStruct->internalCode = $internalCode;

        return $errorStruct;
    }

    /**
     * @param string $exceptionClass
     * @return bool
     */
    private function entityException(string $exceptionClass): bool
    {
        return in_array($exceptionClass, $this->entityException);
    }

    /**
     * @param Throwable $exception
     * @return Dynamic
     */
    private function entityHandler(Throwable $exception): Dynamic
    {
        $errorStruct = new Dynamic();

        $statusCode = 500;
        $type = self::SERVER_ERROR;
        $description = Message::MS099999;
        $internalCode = Code::MS099999;

        if ($exception instanceof EntityNotFoundException) {
            $description = Message::MS000007;
            $statusCode = 404;
            $type = self::RESOURCE_NOT_FOUND;
            $internalCode = Code::MS000007;
        }

        if ($exception instanceof InvalidEntityParametersUpdate) {
            $description = $exception->getMessage();
            $statusCode = 401;
            $type = self::UNAUTHORIZED;
        }

        $errorStruct->statusCode = $statusCode;
        $errorStruct->description = $description;
        $errorStruct->type = $type;
        $errorStruct->internalCode = $internalCode;

        return $errorStruct;
    }
}
