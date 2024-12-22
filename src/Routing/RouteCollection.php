<?php

namespace App\Routing;

class RouteCollection
{
    private array $routes     = [];
    private string $prefix    = '';
    private array $middleware = [];

    public function add(string $method, string $path, callable|string|array $handler): void
    {
        $this->routes[] = [
            'method'     => $method,
            'path'       => $this->prefix . $path,
            'handler'    => $handler,
            'middleware' => $this->middleware,
        ];
    }

    public function group(array $options, callable $callback): void
    {
        $originalPrefix     = $this->prefix;
        $originalMiddleware = $this->middleware;

        if (isset($options['prefix'])) {
            $this->prefix .= $options['prefix'];
        }
        if (isset($options['middleware'])) {
            $this->middleware = array_merge($this->middleware, $options['middleware']);
        }

        $callback($this);

        $this->prefix     = $originalPrefix;
        $this->middleware = $originalMiddleware;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}