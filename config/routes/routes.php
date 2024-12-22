<?php

use App\Router;
use App\Controllers\HomeController;

// Define your routes here
Router::add('GET', '/', [HomeController::class, 'index']);
Router::add('GET', '/about', [HomeController::class, 'about']);