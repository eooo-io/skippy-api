<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$routes = new Symfony\Component\Routing\RouteCollection();

$routes->add('home', new Symfony\Component\Routing\Route('/', [
    '_controller' => function (Request $request) {
        return new Response('Welcome to SkippyAPI!');
    },
]));

return $routes;
