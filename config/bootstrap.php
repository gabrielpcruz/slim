<?php

use App\Factory\ContainerFactory;
use Slim\App;

// Settings
require __DIR__ . '/../vendor/autoload.php';

$container = (new ContainerFactory())->createInstance();

return $container->get(App::class);