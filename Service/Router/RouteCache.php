<?php

namespace Kim\Router;

class RouteCache
{
    private static array $routes = [];

    private static array $prefix = [];

    private static function checkLevel(array $pattern, array $array, string $value): array
    {
        if ($pattern == []) {
            if (! array_key_exists('/', $array)) {
                $array['/'] = $value;
            }
            return $array;
        }
        if (array_key_exists($pattern[0], $array)) {
            $array[$pattern[0]] = self::checkLevel(array_slice($pattern, 1), $array[$pattern[0]], $value);
        } else {

            $array[$pattern[0]] = self::checkLevel(array_slice($pattern, 1), [], $value);
        }
        return $array;
    }

    public static function addRoute(string|array $method, array $pattern, array|callable $fun): void
    {
        if (is_callable($fun)) {
            return;
        }

        $vars = [];
        $pattern = array_merge(self::$prefix, $pattern);
        foreach ($pattern as $key => $value) {
            if (substr($value, 0, 1) === ':') {
                $pattern[$key] = ':';
                $vars[substr($value, 1)] = $key;
            }
        }

        $routes = self::$routes;
        if (is_array($method)) {
            foreach ($method as $m) {
                $pattern = array_merge($m, $pattern);
                $routes = self::checkLevel(array_merge($m, $pattern), self::$routes, json_encode([
                    'call' => $fun,
                    'vars' => $vars
                ]));
            }
        } else {
            $routes = self::checkLevel(array_merge([$method], $pattern), self::$routes, json_encode([
                'call' => $fun,
                'vars' => $vars
            ]));
        }
        self::$routes = $routes;
    }

    public static function cacheRoutes(string $prefix, string $dir)
    {
        self::$prefix = array_values(array_filter(
            explode('/', strtolower($prefix))
        ));

        require $dir;

        self::$prefix = [];
    }

    public static function match(string $method, array $route)
    {
        $v = self::$routes[$method];
        foreach ($route as $value) {
            if (array_key_exists($value, $v)) {
                $v = $v[$value];
            } elseif (array_key_exists(':', $v)) {
                $v = $v[':'];
            } else {
                return false;
            }
        }

        if (! array_key_exists('/', $v)) {
            return false;
        }
        $v = json_decode($v['/'], true);
        $v['params'] = [];
        foreach ($v['vars'] as $key => $value) {
            $v['params'][$key] = $route[$value];
        }
        return $v;
    }

    public static function load()
    {
        self::$routes = json_decode(file_get_contents('./cache/routes.json'), true);
    }

    public static function save()
    {
        file_put_contents('./cache/routes.json', json_encode(self::$routes));
    }
}
