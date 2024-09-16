<?php

return [
    'default' => [
        'driver' => 'mysql',
        'host' => '172.17.0.3',
        'database' => 'slim',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],

    'sqlite' => [
        'driver' => 'sqlite',
        'host' => 'localhost',
        'database' => STORAGE_PATH . '/database/db.sqlite',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]
];
