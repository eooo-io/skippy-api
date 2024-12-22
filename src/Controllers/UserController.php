<?php

namespace App\Controllers;

use App\Models\User;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

class UserController
{
    public function index(Request $request, Response $response): void
    {
        $users = User::all();
        $response->header("Content-Type", "application/json");
        $response->end($users->toJson());
    }

    public function create(Request $request, Response $response): void
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['email'])) {
            $response->status(400);
            $response->end(json_encode(['error' => 'Invalid input']));
            return;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $response->header("Content-Type", "application/json");
        $response->end($user->toJson());
    }

    public function delete(Request $request, Response $response): void
    {
        $id = $request->get['id'] ?? null;

        if (!$id || !User::find($id)) {
            $response->status(404);
            $response->end(json_encode(['error' => 'User not found']));
            return;
        }

        User::destroy($id);
        $response->header("Content-Type", "application/json");
        $response->end(json_encode(['message' => 'User deleted']));
    }
}