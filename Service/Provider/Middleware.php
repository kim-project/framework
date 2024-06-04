<?php

namespace Kim\Support\Provider;

use Kim\Service\Request\Request;
use Kim\Support\Helpers\Response;

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
