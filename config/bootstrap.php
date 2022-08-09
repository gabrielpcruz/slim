<?php

use App\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = App::bootstrap();

    $settings = App::settings();

    error_reporting($settings->get('error.error_reporting'));
    ini_set('display_errors', $settings->get('error.display_errors'));
    ini_set('display_startup_errors', $settings->get('error.display_startup_errors'));

    // Timezone
    date_default_timezone_set($settings->get('timezone'));

    return $app;
} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {
    var_dump($exception);
}