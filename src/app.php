<?php

use App\App;
use DI\DependencyException;
use DI\NotFoundException;

require "vendor/autoload.php";

$container = new DI\Container();

try {
    $app = $container->get(App::class);
    $app->run();
} catch (DependencyException | NotFoundException $e) {
    die($e->getMessage());
}

