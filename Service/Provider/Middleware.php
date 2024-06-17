<?php

namespace Kim\Provider;

use Kim\Request\Request;
use Kim\Support\Response;

interface Middleware
{
    /**
     * The middleware's handler
     *
     * @param Request $request The request
     * @param callable $next
     *
     * @return Response The server's response
     */
    public function handle(Request $request, callable $next): Response;
}
