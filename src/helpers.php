<?php

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

if (!function_exists("container")) {
    return App::getContainer();
}


if (!function_exists("view")) {
    /**
     * @throws SyntaxError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws DependencyException
     * @throws LoaderError
     */
    function view($template, $args = []): string
    {
        /** @var Environment $view */
        $view = App::getContainer()->get('twig');

        return $view->render($template, $args);
    }
}
