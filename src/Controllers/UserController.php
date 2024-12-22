<?php

namespace App\Controllers;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use App\Models\User;

class UserController
{
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
        $response->header('Content-Type', 'application/json');
        $response->end(json_encode(['message' => 'User deleted']));
    }
}