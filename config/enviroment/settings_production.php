<?php

// Settings
$productionSettings = [];

$productionSettings['error'] = [
    'slashtrace' => 0,
    'error_reporting' => 0,
    'display_errors' => 0,
    'display_startup_errors' => 0,
];

$productionSettings['view'] = [
    'settings' => [
        'cache' => ROOT_PATH . '/storage/cache/views',
        'debug' => false,
        'auto_reload' => true,
    ],
];

return $productionSettings;