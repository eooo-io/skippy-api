<?php

use Mockery;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use App\Routing\RouteCollection;
use App\Routing\Router;
use App\Controllers\HomeController;

afterEach(function () {
    Mockery::close();
});

it('can route to the index page', function () {
    // Create and register routes
    $routeCollection = new RouteCollection();
    $routeCollection->add('GET', '/v1/', [HomeController::class, 'index']);

    $router = new Router($routeCollection);

    // Mock request and response
    $request = Mockery::mock(Request::class);
    $request->server = ['request_method' => 'GET', 'request_uri' => '/v1/'];

    $response = Mockery::mock(Response::class);
    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(json_encode(['message' => 'Welcome to SkippyAPI!']));

    $router->dispatch($request, $response);
});