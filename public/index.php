<?php

use OpenSwoole\Http\Server;
use App\Router;
use App\Controllers\HomeController;

require __DIR__ . '/../vendor/autoload.php';

// Define routes
Router::add('GET', '/', [HomeController::class, 'index']);
Router::add('GET', '/about', [HomeController::class, 'about']);

$server = new Server("0.0.0.0", 8080);

$server->on("request", function ($request, $response) {
    Router::handle($request, $response);
});

$server->start();