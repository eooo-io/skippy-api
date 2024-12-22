<?php

namespace App\Middleware;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

class LoggingMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        error_log('Request: ' . $request->server['request_uri']);
    }
}