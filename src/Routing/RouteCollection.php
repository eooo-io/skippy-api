<?php

namespace App\Routing;

class RouteCollection
{
    private array $routes = [];
    private string $prefix = '';

    public function add(string $method, string $path, callable|string|array $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $this->prefix . $path,
            'handler' => $handler,
        ];
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}