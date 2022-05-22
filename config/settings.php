<?php

// Configure defaults for the whole application.

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['tests'] = $settings['root'] . '/tests';
$settings['public'] = $settings['root'] . '/public';


$settings['template'] = [
    'templates_path' => $settings['root'] . '/resources/views',
    'cache_path' => $settings['root'] . '/storage/cache/views'
];

return $settings;