<?php

use App\Routing\RouteCollection;
use App\Controllers\HomeController;
use App\Controllers\UserController;

$routeCollection->setPrefix('/v1');

// Correct paths in routes.php
$routeCollection->add('GET', '/users', [UserController::class, 'index']);
$routeCollection->add('POST', '/users', [UserController::class, 'create']);
$routeCollection->add('DELETE', '/users', [UserController::class, 'delete']);

// Define versioned routes
$routeCollection->add('GET', '/', [HomeController::class, 'index']);
$routeCollection->add('GET', '/about', [HomeController::class, 'about']);

// User routes
$routeCollection->add('GET', '/users', [UserController::class, 'index']);
$routeCollection->add('POST', '/users', [UserController::class, 'create']);
$routeCollection->add('DELETE', '//users', [UserController::class, 'delete']);