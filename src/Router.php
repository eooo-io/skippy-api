<?php

namespace App;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

class Router
{
    private static array $routes = [];

    public static function add(string $method, string $path, callable|string|array $handler)
    {
        self::$routes[] = compact('method', 'path', 'handler');
    }

    public static function handle(Request $request, Response $response)
    {
        $method = $request->server['request_method'];
        $path = $request->server['request_uri'];

        foreach (self::$routes as $route) {
            if ($method === $route['method'] && $path === $route['path']) {
                $handler = $route['handler'];

                // Handle array-based callables
                if (is_array($handler) && count($handler) === 2) {
                    [$class, $method] = $handler;
                    $instance = new $class();
                    return $instance->$method($request, $response);
                }

                // Handle direct callables
                if (is_callable($handler)) {
                    return $handler($request, $response);
                }
            }
        }

        // Route not found
        $response->status(404);
        $response->header("Content-Type", "application/json");
        $response->end(json_encode(["error" => "Route not found"]));
    }
}