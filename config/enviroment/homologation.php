<?php

// Settings
$homologationSettings = [];

$homologationSettings['error'] = [
    'slashtrace' => 0,
    'error_reporting' => 0,
    'display_errors' => 0,
    'display_startup_errors' => 0,
];


$homologationSettings['view'] = [
    'settings' => [
        'cache' => $homologationSettings['root'] . '/storage/cache/views',
        'debug' => false,
        'auto_reload' => true,
    ],
];

return $homologationSettings;