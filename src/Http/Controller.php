<?php

namespace App\Http;

abstract class Controller
{

    /**
     * @param array $data
     * @param int $status
     * @return ResponseInterface
     */
    public function responseJSON(
        array $data,
        int   $status = 200
    ): ResponseInterface
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);

//        $this->
    }
}