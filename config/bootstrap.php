<?php

use App\App;

// Settings
require __DIR__ . '/../vendor/autoload.php';

try {
    $container = App::getContainer();

    // Configure defaults for the whole application.
    $settings = $container->get('settings');

    error_reporting($settings->get('error.error_reporting'));
    ini_set('display_errors', $settings->get('error.display_errors'));
    ini_set('display_startup_errors', $settings->get('error.display_startup_errors'));

    // Timezone
    date_default_timezone_set($settings->get('timezone'));

    return App::getInstace();
} catch (Error|Exception $error) {
    echo "error";

    throw $error;
}
