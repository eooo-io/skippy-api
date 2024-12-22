<?php

namespace App\Routing;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

interface RouterInterface
{
    public function dispatch(Request $request, Response $response): void;
}