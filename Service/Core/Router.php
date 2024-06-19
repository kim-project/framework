<?php

namespace Kim\Router;

use Kim\Core\Container;

class Router
{
    private static Container $container;

    private static array $routes = [];

    private static array $handlers = [];

    public function route(array|string $method, string $pattern, array|callable $handler)
    {
        $pattern = self::normalizeRoute($pattern);
        $pattern = ['route' => $pattern, 'vars' => array_keys(preg_grep('/^:/', $pattern))];
        if (is_array($method)) {
            foreach ($method as $value) {
                self::$routes[strtoupper($value)] = $pattern;
            }
        } else {
            self::$routes[strtoupper($method)] = $pattern;
        }
    }

    public function get(string $pattern, array|callable $handler)
    {
        return $this->route('GET', $pattern, $handler);
    }

    public function post(string $pattern, array|callable $handler)
    {
        return $this->route('POST', $pattern, $handler);
    }

    public function put(string $pattern, array|callable $handler)
    {
        return $this->route('PUT', $pattern, $handler);
    }

    public function delete(string $pattern, array|callable $handler)
    {
        return $this->route('DELETE', $pattern, $handler);
    }

    public function any(string $pattern, array|callable $handler)
    {
        return $this->route('any', $pattern, $handler);
    }

    private static function normalizeRoute(string $pattern): array
    {
        return array_values(array_filter(
            explode('/', strtolower($pattern))
        ));
    }
}
