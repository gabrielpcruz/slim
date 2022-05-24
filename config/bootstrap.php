<?php

use App\App;

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = App::bootstrap();

    $container = $app->getContainer();

    // Configure defaults for the whole application.
    $settings = $container->get('settings');

    error_reporting($settings->get('error.error_reporting'));
    ini_set('display_errors', $settings->get('error.display_errors'));
    ini_set('display_startup_errors', $settings->get('error.display_startup_errors'));

    // Timezone
    date_default_timezone_set($settings->get('timezone'));

    return $app;
} catch (Throwable $error) {

}