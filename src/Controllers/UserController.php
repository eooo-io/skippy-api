<?php

namespace App\Controllers;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use App\Models\User;

class UserController
{
    public function index(Request $request, Response $response): void
    {
        $users = User::all();
        $response->header('Content-Type', 'application/json');
        $response->end($users->toJson());
    }

    public function show(Request $request, Response $response, array $params): void
    {
        $user = User::find($params['id']);
        if (!$user) {
            $response->status(404);
            $response->header('Content-Type', 'application/json');
            $response->end(json_encode(['error' => 'User not found']));
            return;
        }

        $response->header('Content-Type', 'application/json');
        $response->end($user->toJson());
    }

    public function create(Request $request, Response $response): void
    {
        $data = json_decode($request->getContent(), true);

        $user = User::create($data);

        $response->header('Content-Type', 'application/json');
        $response->end($user->toJson());
    }

    public function delete(Request $request, Response $response, array $params): void
    {
        $user = User::find($params['id']);

        if (!$user) {
            $response->status(404);
            $response->header('Content-Type', 'application/json');
            $response->end(json_encode(['error' => 'User not found']));
            return;
        }

        $user->delete();

        $response->status(200); // Ensure this is called
        $response->header('Content-Type', 'application/json');
        $response->end(json_encode(['message' => 'User deleted']));
    }
}