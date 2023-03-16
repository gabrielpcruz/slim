<?php

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Illuminate\Database\Query\Builder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

if (!function_exists('turnNameSpacePathIntoArray')) {
    function turnNameSpacePathIntoArray($nameSpacePath, $namespace, $excludeFiles = [], $excludePaths = []): array
    {
        $items = [];

        $pathsToExclude = ['.', '..'];

        foreach ($excludePaths as $path) {
            $pathsToExclude[] = $path;
        }

        foreach (scandir($nameSpacePath) as $class) {
            $isExcludePath = in_array($class, $pathsToExclude);
            $isExcludeFile = in_array($class, $excludeFiles);

            if (!$isExcludePath && !$isExcludeFile) {
                $items[] = $namespace . str_replace('.php', '', $class);
            }
        }

        return $items;
    }
}

if (!function_exists('getConsole')) {
    function getConsole($container): Application
    {
        $console = new Application();

        $commands = (require_once $container->get('settings')->get('file.commands'));

        if (!empty($commands)) {
            foreach ($commands as $commandClass) {
                $console->add($container->get($commandClass));
            }
        }

        return $console;
    }
}

if (!function_exists('command')) {
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    function command($command): string
    {
        $application = getConsole(App::container());
        $application->setAutoExit(false);

        $input = new ArrayInput($command);

        $output = new BufferedOutput();
        $application->run($input, $output);

        return $output->fetch();
    }
}

if (!function_exists('redirect')) {
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    function redirect($route): ResponseInterface
    {
        $response = App::getInstace()->getResponseFactory()->createResponse();

        return $response->withHeader('Location', $route);
    }
}

if (!function_exists('flash')) {
    /**
     * @return Messages
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function flash(): Messages
    {
        return App::flash();
    }
}

if (!function_exists('datePeriod')) {
    /**
     * @param int $days
     * @param int $hours
     * @param int $minutes
     * @return DateInterval
     * @throws Exception
     */
    function datePeriod(int $days = 0, int $hours = 0, int $minutes = 0): DateInterval
    {
        $expressionPeriod = 'P0Y%sDT%sH%sM';

        $expressionPeriod = sprintf(
            $expressionPeriod,
            $days,
            $hours,
            $minutes,
        );

        return new DateInterval($expressionPeriod);
    }
}

if (!function_exists('str_rand')) {
    /**
     * @param int $length
     * @return string
     * @throws Exception
     *
     * @link https://www.php.net/manual/en/function.random-bytes.php
     */
    function str_rand(int $length = 64): string
    {
        $length = ($length < 4) ? 4 : $length;

        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }
}

if (!function_exists('slugify')) {
    function slugify($text, string $divider = '-'): string
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

if (!function_exists('dd')) {
    function dd($expression) {

        var_dump($expression);
        die;
    }
}

if (!function_exists('dump_query')) {
    function dump_query(Builder $query) {

        $gramar = $query->getGrammar();

        dd(
            $gramar->compileSelect(
                $query
            )
        );
    }
}
