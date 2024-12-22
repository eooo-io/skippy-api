<?php

use OpenSwoole\Http\Server;
use App\Router;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Include the routes file
require __DIR__ . '/../config/routes/routes.php';

$server = new Server("0.0.0.0", 8080);

$server->on("request", function ($request, $response) {
    Router::handle($request, $response);
});

$server->start();