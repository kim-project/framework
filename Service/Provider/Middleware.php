<?php

namespace Kim\Support\Provider;

use Kim\Service\Request\Request;
use Kim\Support\Helpers\Response;

abstract class Middleware
{
    abstract public function handle(Request $request, callable $next): Response;
}
