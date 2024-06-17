<?php

namespace Kim\Router;

use Closure;
use Kim\Request\Request;
use Kim\Support\Response;
use Kim\Provider\Middleware;

class MiddlewareHandle
{
    private Request $request;
    private array $middlewares;
    private Closure $next;
    private array|Closure $call;
    private array $input;

    public function __construct(Request $request, array $middlewares, array|callable $next, array $input)
    {
        $this->request = $request;
        $this->middlewares = $middlewares;
        $this->call = $next;
        $this->next = function (Request $request) {
            $this->request = $request;

            if(array_key_exists('request', $this->input)) {
                $this->input['request'] = $request;
            }

            $call = $this->call;
            return $call(...$this->input);
        };
        $this->input = $input;
    }

    public function handle(): Response
    {
        $next = $this->next;
        foreach ($this->middlewares as $middleware) {
            if(!$middleware instanceof Middleware) {
                throw new \Exception("$middleware is not a Middleware");
            }
            $next = fn (Request $request): Response => $middleware->handle($request, $next);
        }
        return $next($this->request);
    }
}
