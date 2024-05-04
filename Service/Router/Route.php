<?php

namespace Kim\Service\Router;

class Route
{
    /**
     * set route for controller's method with GET method
     *
     * @param  string  $url  Route pattern
     * @param  string  $function  Controller's method name
     */
    public static function get(string $url, string $function): array
    {
        return ['method' => 'GET', 'route' => $url, 'function' => $function];
    }

    /**
     * set route for controller's method with POST method
     *
     * @param  string  $url  Route pattern
     * @param  string  $function  Controller's method name
     */
    public static function post(string $url, string $function): array
    {
        return ['method' => 'POST', 'route' => $url, 'function' => $function];
    }

    /**
     * set route for controller's method with PUT method
     *
     * @param  string  $url  Route pattern
     * @param  string  $function  Controller's method name
     */
    public static function put(string $url, string $function): array
    {
        return ['method' => 'PUT', 'route' => $url, 'function' => $function];
    }

    /**
     * set route for controller's method with DELETE method
     *
     * @param  string  $url  Route pattern
     * @param  string  $function  Controller's method name
     */
    public static function delete(string $url, string $function): array
    {
        return ['method' => 'DELETE', 'route' => $url, 'function' => $function];
    }

    /**
     * set route for controller's method with any methods
     *
     * @param  string  $url  Route pattern
     * @param  string  $function  Controller's method name
     */
    public static function any(string $url, string $function): array
    {
        return ['method' => 'any', 'route' => $url, 'function' => $function];
    }

    /**
     * set route for controller's method with method(s)
     *
     * @param  array|string  $method  Accepted method(s) for this route
     * @param  string  $url  Route pattern
     * @param  string  $function  Controller's method name
     */
    public static function route(array|string $method, string $url, string $function): array
    {
        return ['method' => $method, 'route' => $url, 'function' => $function];
    }
}
