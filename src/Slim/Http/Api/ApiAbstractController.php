<?php

namespace App\Slim\Http\Api;

use App\Slim\Utils\Dynamic;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Slim\Http\AbstractController;

class ApiAbstractController extends AbstractController
{
    /**
     * @var Dynamic
     */
    protected Dynamic $payloadReponse;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->initPayloadResponse();
        parent::__construct($container);
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function toJson(ResponseInterface $response): ResponseInterface
    {
        $dataOnlyAttributes = ['code'];

        if (empty($this->payloadResponse()->message)) {
            $dataOnlyAttributes[] = 'message';
        }

        $data = $this->payloadResponse()->whithout($dataOnlyAttributes);
        $code = (int) $this->payloadResponse()->code;

        $encodedJson = json_encode($data, JSON_PRETTY_PRINT);

        if (!is_string($encodedJson)) {
            $encodedJson = '';
        }

        $response->getBody()->write($encodedJson);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($code);
    }

    /**
     * @return void
     */
    private function initPayloadResponse(): void
    {
        $this->payloadReponse = new Dynamic();

        $this->payloadReponse->code = 200;
        $this->payloadReponse->message = "";
        $this->payloadReponse->data = [];
    }

    /**
     * @param Request $request
     * @return Dynamic
     */
    protected function fromJson(Request $request): Dynamic
    {
        return Dynamic::fromJson($request->getBody()->getContents()) ?? new Dynamic();
    }

    /**
     * @return Dynamic
     */
    protected function payloadResponse(): Dynamic
    {
        return $this->payloadReponse;
    }
}
