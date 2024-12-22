<?php

use OpenSwoole\Http\Server;
use App\Routing\RouteCollection;
use App\Routing\Router;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Initialize routes
$routeCollection = new RouteCollection();
require __DIR__ . '/../config/routes/routes.php';

// Initialize the router
$router = new Router($routeCollection);

$server = new Server("0.0.0.0", 8080);

$server->on("request", function ($request, $response) use ($router) {
    $router->dispatch($request, $response);
});

$server->start();