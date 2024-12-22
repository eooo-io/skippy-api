<?php

use Mockery;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use App\Routing\RouteCollection;
use App\Routing\Router;
use App\Controllers\HomeController;
use App\Controllers\UserController;

beforeEach(function () {
    setupDatabase(); // Sets up the in-memory SQLite database for testing
});

afterEach(function () {
    Mockery::close(); // Cleans up Mockery after each test
});

it('can route to the index page', function () {
    $routeCollection = new RouteCollection();
    $routeCollection->add('GET', '/v1/', [HomeController::class, 'index']);

    $router = new Router($routeCollection);

    // Mock request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $request->server = ['request_method' => 'GET', 'request_uri' => '/v1/'];

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(json_encode(['message' => 'Welcome to SkippyAPI!']));

    $router->dispatch($request, $response);
});

it('can route to the about page', function () {
    $routeCollection = new RouteCollection();
    $routeCollection->add('GET', '/v1/about', [HomeController::class, 'about']);

    $router = new Router($routeCollection);

    // Mock request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $request->server = ['request_method' => 'GET', 'request_uri' => '/v1/about'];

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(json_encode(['message' => 'About SkippyAPI']));

    $router->dispatch($request, $response);
});

it('can route to list users', function () {
    $routeCollection = new RouteCollection();
    $routeCollection->add('GET', '/v1/users', [UserController::class, 'index']);

    $router = new Router($routeCollection);

    // Add test user to database
    App\Models\User::create(['name' => 'John Doe', 'email' => 'john@example.com']);

    // Mock request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $request->server = ['request_method' => 'GET', 'request_uri' => '/v1/users'];

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(Mockery::on(function ($data) {
        return str_contains($data, 'John Doe');
    }));

    $router->dispatch($request, $response);
});

it('can route to create a user', function () {
    $routeCollection = new RouteCollection();
    $routeCollection->add('POST', '/v1/users', [UserController::class, 'create']);

    $router = new Router($routeCollection);

    // Mock request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $request->shouldReceive('getContent')->once()->andReturn(json_encode([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]));

    $request->server = ['request_method' => 'POST', 'request_uri' => '/v1/users'];

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(Mockery::on(function ($data) {
        $user = json_decode($data, true);
        return $user['name'] === 'Jane Doe' && $user['email'] === 'jane@example.com';
    }));

    $router->dispatch($request, $response);

    assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
});

it('can route to delete a user', function () {
    $routeCollection = new RouteCollection();
    $routeCollection->add('DELETE', '/v1/users', [UserController::class, 'delete']);

    $router = new Router($routeCollection);

    // Add test user to database
    $user = App\Models\User::create(['name' => 'Mark Smith', 'email' => 'mark@example.com']);

    // Mock request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $request->server = ['request_method' => 'DELETE', 'request_uri' => '/v1/users'];
    $request->get = ['id' => $user->id];

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(json_encode(['message' => 'User deleted']));

    $router->dispatch($request, $response);

    assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});