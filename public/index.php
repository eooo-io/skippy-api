<?php

use App\Routing\RouteCollection;
use App\Routing\Router;
use OpenSwoole\Http\Server;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Bootstrap Eloquent
require __DIR__ . '/../config/database.php';

// Set up routes with versioning
$routeCollection = new RouteCollection();
$routeCollection->setPrefix('/v1');
require __DIR__ . '/../config/routes/routes.php';

// Initialize the router
$router = new Router($routeCollection);

$server = new Server('0.0.0.0', 8080);

$server->on('request', function($request, $response) use ($router) {
    $router->dispatch($request, $response);
});

$server->start();