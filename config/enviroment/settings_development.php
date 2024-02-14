<?php

// Settings
$developmentSettings = [];

$developmentSettings['error'] = [
    'slashtrace' => 0,
    'error_reporting' => 1,
    'display_errors' => 1,
    'display_startup_errors' => 1,
];

$developmentSettings['view'] = [
    'settings' => [
        'cache' => ROOT_PATH . '/storage/cache/views',
        'debug' => false,
        'auto_reload' => true,
    ],
];

return $developmentSettings;
