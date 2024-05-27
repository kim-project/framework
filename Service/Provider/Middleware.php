<?php

namespace Kim\Support\Provider;

use Kim\Service\Request\Request;
use Kim\Support\Helpers\Response;

interface Middleware
{
    public function handle(Request $request, callable $next): Response;
}
