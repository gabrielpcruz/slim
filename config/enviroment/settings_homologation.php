<?php

// Settings
$homologationSettings = [];

$homologationSettings['error'] = [
    'slashtrace' => 1,
    'error_reporting' => 1,
    'display_errors' => 1,
    'display_startup_errors' => 1,
];

$homologationSettings['view'] = [
    'settings' => [
        'cache' => ROOT_PATH . '/storage/cache/views',
        'debug' => false,
        'auto_reload' => true,
    ],
];

return $homologationSettings;
