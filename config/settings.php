<?php

// Configure defaults for the whole application.

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);

$settings['path'] = [
    'tests' => $settings['root'] . '/tests',
    'public' => $settings['root'] . '/public',
    'config' => $settings['root'] . '/config',
    'storage' => $settings['root'] . '/storage',
    'database' => $settings['root'] . '/config/database',
    'migration' => $settings['root'] . '/src/Console/Migration',
];

$settings['file'] = [
        'providers' => $settings['path']['config'] . '/provider/providers.php',
        'commands' => $settings['path']['config'] . '/command/commands.php',
        'database' => $settings['path']['config'] . '/database/database.php',
];

$settings['error'] = [
    'slashtrace' => 1, // Exibir erros com uma interface gráfica
    'error_reporting' => 1,
    'display_errors' => 1,
    'display_startup_errors' => 1,
];

$settings['timezone'] = 'America/Sao_Paulo';

$settings['view'] = [
    'path' => $settings['root'] . '/resources/views',

    'templates' => [
        "api" => $settings['root'] . '/resources/views/api',
        "error" => $settings['root'] . '/resources/views/error',
        "console" => $settings['root'] . '/resources/views/console',
        "site" => $settings['root'] . '/resources/views/site',
        "email" => $settings['root'] . '/resources/views/email',
        "layout" => $settings['root'] . '/resources/views/layout',
    ],

    'settings' => [
        'cache' => $settings['root'] . '/storage/cache/views',
        'debug' => true,
        'auto_reload' => true,
    ],
];

return $settings;