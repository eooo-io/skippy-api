<?php
use OpenSwoole\Http\Server;

// Load Composer's autoloader from the vendor directory
require __DIR__ . '/../vendor/autoload.php';

$server = new Server("0.0.0.0", 8080);

$server->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello, SkippyAPI with Open Swoole!");
});

$server->start();