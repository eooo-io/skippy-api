<?php

namespace App\Routing;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

class Router implements RouterInterface
{
    private RouteCollection $routeCollection;

    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }
    public function dispatch(Request $request, Response $response): void
    {
        $method = $request->server['request_method'];
        $path = $request->server['request_uri'];
        $routes = $this->routeCollection->getRoutes();

        foreach ($routes as $route) {
            if ($method === $route['method'] && $path === $route['path']) {
                error_log("Matched Route: {$method} {$path}");
                $handler = $route['handler'];

                if (is_array($handler)) {
                    [$class, $method] = $handler;
                    $instance = new $class();
                    $instance->$method($request, $response);
                    return;
                }

                if (is_callable($handler)) {
                    $handler($request, $response);
                    return;
                }
            }
        }

        error_log("Route not found: {$method} {$path}");
        $response->status(404);
        $response->header("Content-Type", "application/json");
        $response->end(json_encode(["error" => "Route not found"]));
    }
}