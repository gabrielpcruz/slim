<?php

namespace App\Http\Api;

use App\Http\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use stdClass;

class ApiController extends Controller
{
    /**
     * @param ResponseInterface $response
     * @param array $data
     * @param int $status
     * @return ResponseInterface
     */
    public function responseJSON(
        ResponseInterface $response,
        array $data,
        int $status = 200
    ): ResponseInterface {
        $encodedJson = json_encode($data, JSON_PRETTY_PRINT);

        if (!is_string($encodedJson)) {
            $encodedJson = '';
        }

        $response->getBody()->write($encodedJson);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    /**
     * @param Request $request
     * @return stdClass
     */
    protected function getJsonBody(Request $request): stdClass
    {
        return json_decode($request->getBody()->getContents()) ?? new stdClass();
    }
}
