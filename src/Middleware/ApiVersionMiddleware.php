<?php

namespace App\Middleware;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

class ApiVersionMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        $version = explode('/', trim($request->server['request_uri'], '/'))[0];
        if ($version !== 'v1') {
            $response->status(400);
            $response->header('Content-Type', 'application/json');
            $response->end(json_encode(['error' => 'Unsupported API version']));
        }
    }
}