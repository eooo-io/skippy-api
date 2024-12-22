<?php

namespace App\Routing;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

class Router
{
    private RouteCollection $routeCollection;

    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function dispatch(Request $request, Response $response): void
    {
        $method = $request->server['request_method'];
        $path   = $request->server['request_uri'];
        $routes = $this->routeCollection->getRoutes();

        foreach ($routes as $route) {
            $params = $this->matchRoute($path, $route['path']);
            if ($method === $route['method'] && $params !== false) {
                $this->applyMiddleware($route['middleware'], $request, $response);

                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$class, $method] = $handler;
                    $instance         = new $class();
                    $instance->$method($request, $response, $params);
                } elseif (is_callable($handler)) {
                    $handler($request, $response, $params);
                }

                return;
            }
        }

        $response->status(404);
        $response->header('Content-Type', 'application/json');
        $response->end(json_encode(['error' => 'Route not found']));
    }

    private function matchRoute(string $requestPath, string $routePath): array|bool
    {
        $requestParts = explode('/', trim($requestPath, '/'));
        $routeParts   = explode('/', trim($routePath, '/'));

        if (count($requestParts) !== count($routeParts)) {
            return false;
        }

        $params = [];
        foreach ($routeParts as $index => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $params[trim($part, '{}')] = $requestParts[$index];
            } elseif ($part !== $requestParts[$index]) {
                return false;
            }
        }

        return $params;
    }

    private function applyMiddleware(array $middleware, Request $request, Response $response): void
    {
        foreach ($middleware as $middlewareClass) {
            $instance = new $middlewareClass();
            $instance->handle($request, $response);
        }
    }
}