<?php

//// Configure defaults for the whole application.
//
//// Error reporting
//error_reporting(1);
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//
//// Timezone
//date_default_timezone_set('America/Sao_Paulo');

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['tests'] = $settings['root'] . '/tests';
$settings['public'] = $settings['root'] . '/public';

$settings['error'] = [
    'slashtrace' => 1,
    'error_reporting' => 1,
    'display_errors' => 1,
    'display_startup_errors' => 1,
];

$settings['timezone'] = 'America/Los_Angeles';

$settings['view'] = [
    'path' => $settings['root'] . '/resources/views',

    'templates' => [
        "error" => $settings['root'] . '/resources/views/error',
        "console" => $settings['root'] . '/resources/views/console',
        "site" => $settings['root'] . '/resources/views/site',
        "email" => $settings['root'] . '/resources/views/email',
    ],

    'settings' => [
        'cache' => $settings['root'] . '/storage/cache/views',
        'debug' => true,
        'auto_reload' => true,
    ],
];

return $settings;