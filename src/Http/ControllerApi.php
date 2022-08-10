<?php

namespace App\Http;

use Psr\Http\Message\ResponseInterface;

class ControllerApi extends Controller
{
    /**
     * @param ResponseInterface $response
     * @param array $data
     * @param int $status
     * @return ResponseInterface
     */
    public function responseJSON(
        ResponseInterface $response,
        array             $data,
        int               $status = 200
    ): ResponseInterface
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);

        $response->getBody()->write($json);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}