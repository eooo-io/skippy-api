<?php

namespace App\Controllers;

use OpenSwoole\Http\Response;

class HomeController
{
    public function index($request, Response $response)
    {
        $response->header("Content-Type", "application/json");
        $response->end(json_encode(["message" => "Welcome to SkippyAPI!"]));
    }

    public function about($request, Response $response)
    {
        $response->header("Content-Type", "application/json");
        $response->end(json_encode(["message" => "About SkippyAPI"]));
    }
}