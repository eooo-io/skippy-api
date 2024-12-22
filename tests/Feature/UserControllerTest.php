<?php

use Mockery;
use App\Models\User;
use App\Controllers\UserController;
use App\Routing\RouteCollection; // Add this line
use App\Routing\Router;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

beforeEach(function () {
    setupDatabase(); // Ensure the database is set up before each test
});

afterEach(function () {
    Mockery::close(); // Cleanup Mockery after each test
});

it('can list users', function () {
    // Create test users
    User::create(['name' => 'John Doe', 'email' => 'john@example.com']);

    // Mock the request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(Mockery::on(function ($data) {
        return str_contains($data, 'John Doe');
    }));

    // Invoke the controller's index method
    $controller = new UserController();
    $controller->index($request, $response);
});

it('can get a user by ID', function () {
    $user = User::create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);

    // Mock the request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(Mockery::on(function ($data) use ($user) {
        $responseData = json_decode($data, true);
        return $responseData['id'] === $user->id && $responseData['name'] === $user->name;
    }));

    // Call the show method
    $controller = new UserController();
    $controller->show($request, $response, ['id' => $user->id]);
});

it('can create a new user', function () {
    // Mock the request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $request->shouldReceive('getContent')->once()->andReturn(json_encode([
        'name' => 'New User',
        'email' => 'newuser@example.com',
    ]));

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(Mockery::on(function ($data) {
        $responseData = json_decode($data, true);
        return $responseData['name'] === 'New User' && $responseData['email'] === 'newuser@example.com';
    }));

    // Invoke the create method
    $controller = new UserController();
    $controller->create($request, $response);

    // Verify the user exists in the database
    assertDatabaseHas('users', ['name' => 'New User', 'email' => 'newuser@example.com']);
});

it('can route to delete a user', function () {
    $routeCollection = new RouteCollection();
    $routeCollection->add('DELETE', '/v1/users/{id}', [UserController::class, 'delete']);

    $router = new Router($routeCollection);

    // Create a test user in the database
    $user = User::create(['name' => 'Mark Smith', 'email' => 'mark@example.com']);

    // Mock the request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    // Mock the request parameters
    $request->server = ['request_method' => 'DELETE', 'request_uri' => '/v1/users/' . $user->id];

    // Mock the response methods
    $response->shouldReceive('status')->once()->with(200); // Define the status method
    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(json_encode(['message' => 'User deleted']));

    // Dispatch the route
    $router->dispatch($request, $response);

    // Assert that the user was deleted
    assertDatabaseMissing('users', ['id' => $user->id]);
});