<?php

use App\App;

if (!function_exists("container")) {
    return App::getContainer();
}

