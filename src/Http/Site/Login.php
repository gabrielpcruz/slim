<?php

namespace App\Http\Site;

use App\Business\Rice\RiceBusiness;
use App\Http\ControllerSite;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Login extends ControllerSite
{
    public function index(Request $request, Response $response): Response
    {
        $riceBusiness = new RiceBusiness();

        $rices = $riceBusiness->all()->toArray();

        return $this->view(
            $response,
            "@site/login/index",
            compact('rices')
        );
    }
}