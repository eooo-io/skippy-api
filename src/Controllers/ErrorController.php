<?php

namespace App\Controllers;

use OpenSwoole\Http\Response;

class ErrorController
{
    public function notFound(Response $response)
    {
        $response->status(404);
        $response->header('Content-Type', 'application/json');
        $response->end(json_encode(['error' => 'Route not found']));
    }
}