<?php
// Settings
use PHPMailer\PHPMailer\SMTP;

$settings = [];

$rootCli = str_replace('/config/enviroment/settings_default.php', '', __FILE__);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $rootCli);
}

// Path settings
$settings['root'] = ROOT_PATH;

$settings['path'] = [
    'tests' => ROOT_PATH . '/tests',
    'public' => ROOT_PATH . '/public',
    'assets' => 'assets/',
    'config' => ROOT_PATH . '/config',
    'data' => ROOT_PATH . '/data',
    'storage' => ROOT_PATH . '/storage',
    'cache' => ROOT_PATH . '/storage/cache',
    'database' => ROOT_PATH . '/config/database',
    'console' => ROOT_PATH . '/src/Console',
    'migration' => ROOT_PATH . '/src/Migration',
    'seeder' => ROOT_PATH . '/src/Seeder',
    'slim' => [
        'console' => [
            'cache' => ROOT_PATH . '/src/Slim/Console/Cache',
            'database' => ROOT_PATH . '/src/Slim/Console/Database',
            'entity' => ROOT_PATH . '/src/Slim/Console/Entity',
            'migration' => ROOT_PATH . '/src/Slim/Console/Migration',
            'oauth' => ROOT_PATH . '/src/Slim/Console/Oauth',
            'seeder' => ROOT_PATH . '/src/Slim/Console/Seeder',
        ],
        'migration' => ROOT_PATH . '/src/Slim/Migration',
        'seeder' => ROOT_PATH . '/src/Slim/Seeder',
        'twig' => ROOT_PATH . '/src/Slim/Twig',
    ],
    'provider' => ROOT_PATH . '/src/Slim/Provider',
    'repository' => ROOT_PATH . '/src/Repository',
    'entity' => ROOT_PATH . '/src/Entity',
    'files' => [
        'images' => ROOT_PATH . '/storage/images'
    ],
];

$settings['system'] = [
    'maintenance' => 0,
    'maintenance_return' => '2023-07-16 12:07',
    'maintenance_route' => '/maintenance',
    'guest_routes' => [
        '/login',
    ],
    'routes_in_maintenance' => [
    ],
];

$settings['file'] = [
    'providers' => $settings['path']['config'] . '/provider/providers.php',
    'commands' => $settings['path']['config'] . '/command/commands.php',
    'database' => $settings['path']['config'] . '/database/database.php',

    'oauth_private' => $settings['path']['data'] . '/oauth/keys/private.key',
    'oauth_public' => $settings['path']['data'] . '/oauth/keys/public.key',
];

$settings['mailer'] = [

    //PHPMailer settings
    'phpmailer' => [

        //Configs
        'smtp_host' => 'smtp.example.com',
        'smtp_debug' => SMTP::DEBUG_OFF,
        'smtp_exceptions' => false,

        'smtp_port' => 465,
        'smtp_options' => [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ],

        // Auth
        'username' => 'youremail@gmail.com',
        'password' => 'yourpasswordemail',
    ]
];

$settings['error'] = [
    'slashtrace' => 1, // Exibir erros na tela
    'error_reporting' => 1,
    'display_errors' => 1,
    'display_startup_errors' => 1,
];

$settings['timezone'] = 'America/Sao_Paulo';

$settings['view'] = [
    'path' => ROOT_PATH . '/resources/views',

    'templates' => [
        'api' => ROOT_PATH . '/resources/views/api',
        'console' => ROOT_PATH . '/resources/views/console',
        'email' => ROOT_PATH . '/resources/views/email',
        'error' => ROOT_PATH . '/resources/views/error',
        'layout' => ROOT_PATH . '/resources/views/layout',
        'site' => ROOT_PATH . '/resources/views/site',
    ],

    'settings' => [
        'cache' => ROOT_PATH . '/storage/cache/views',
        'debug' => true,
        'auto_reload' => true,
    ],

    'assets' => [
        // Public assets cache directory
        'path' => ROOT_PATH . '/public/assets',

        // Public url base path
        'url_base_path' => ROOT_PATH . '/public/assets',

        // Internal cache directory for the assets
        'cache_path' => ROOT_PATH . '/storage/cache/views',

        'cache_name' => 'assets-cache',

        //  Should be set to 1 (enabled) in production
        'minify' => 1,
    ]
];

return $settings;
