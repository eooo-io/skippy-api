<?php

use Mockery;
use App\Models\User;
use App\Controllers\UserController;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

beforeEach(function () {
    setupDatabase(); // Initializes the in-memory SQLite database
});

afterEach(function () {
    Mockery::close(); // Cleans up Mockery after each test
});

it('can list users', function () {
    // Add a test user
    User::create(['name' => 'John Doe', 'email' => 'john@example.com']);

    // Mock request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(Mockery::on(function ($data) {
        return str_contains($data, 'John Doe');
    }));

    // Call the controller
    $controller = new UserController();
    $controller->index($request, $response);
});

it('can create a user', function () {
    // Mock request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $request->shouldReceive('getContent')->once()->andReturn(json_encode([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]));

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(Mockery::on(function ($data) {
        $user = json_decode($data, true);
        return $user['name'] === 'Jane Doe' && $user['email'] === 'jane@example.com';
    }));

    // Call the controller
    $controller = new UserController();
    $controller->create($request, $response);

    // Assert the user was added to the database
    assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
});

it('can delete a user', function () {
    // Add a test user
    $user = User::create(['name' => 'Mark Smith', 'email' => 'mark@example.com']);

    // Mock request and response
    $request = Mockery::mock(Request::class);
    $response = Mockery::mock(Response::class);

    $request->get = ['id' => $user->id];

    $response->shouldReceive('header')->once()->with('Content-Type', 'application/json');
    $response->shouldReceive('end')->once()->with(json_encode(['message' => 'User deleted']));

    // Call the controller
    $controller = new UserController();
    $controller->delete($request, $response);

    // Assert the user was removed from the database
    assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});