<?php

namespace App\Slim\Container;

interface SlimContainerApp
{
    /**
     * @return array
     */
    public function getDefinitions() : array;
}
