<?php

use App\Routing\RouteCollection;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Middleware\LoggingMiddleware;
use App\Middleware\ApiVersionMiddleware;

$routeCollection->group(['prefix' => '/v1', 'middleware' => [ApiVersionMiddleware::class, LoggingMiddleware::class]], function ($routes) {
    $routes->add('GET', '/', [HomeController::class, 'index']);
    $routes->add('GET', '/about', [HomeController::class, 'about']);
    $routes->add('GET', '/users', [UserController::class, 'index']);
    $routes->add('GET', '/users/{id}', [UserController::class, 'show']); // Dynamic parameter
    $routes->add('POST', '/users', [UserController::class, 'create']);
    $routes->add('DELETE', '/users/{id}', [UserController::class, 'delete']); // Dynamic parameter
});