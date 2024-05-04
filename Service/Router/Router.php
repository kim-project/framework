<?php

namespace Kim\Service\Router;

use Kim\Support\Database\DB;
use Kim\Support\Provider\Controller;

class Router
{
    /**
     * Response data
     *
     * @param  mixed  $response  Data to response
     */
    private static function response(mixed $response): void
    {
        if (is_array($response) || is_object($response)) {
            echo json_encode($response);
        } else {
            echo $response;
        }
        DB::close();
        exit;
    }

    /**
     * create a controller instance
     *
     * @param  string  $class  The Controller class
     */
    private static function getController(string $class): Controller
    {
        $obj = (new $class());
        if (! $obj instanceof Controller) {
            throw new \Exception("$class is not a \app\Controllers\Controller", 503);
        }

        return $obj;
    }

    /**
     * Set routes handler for controller's functions
     *
     * @param  string  $prefix  The prefix for the controller routes prefix
     * @param  string  $class  The Controller class
     * @param  array  $routes  Array of routes defined with Route::$method()
     */
    public static function controller(string $prefix, string $class, array $routes): void
    {
        if (Server::checkRoute($prefix, false) === false) {
            return;
        }

        foreach (array_filter($routes) as $value) {
            if (! Server::checkMethod($value['method'])) {
                continue;
            }
            $route = Server::checkRoute($prefix.'/'.$value['route']);
            if ($route === false) {
                continue;
            }
            $function = $value['function'];
            $obj = self::getController($class);
            $res = $obj->$function(...$route);
            Router::response($res);
        }
    }

    /**
     * Set route handler for specified route and method(s)
     *
     * @param  array|string  $method  string or an array of valid method(s)
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     */
    public static function route(array|string $method, string $route, array|callable $fun): void
    {
        if (! Server::checkMethod($method)) {
            return;
        }
        $route = Server::checkRoute($route);
        if ($route === false) {
            return;
        }
        $res = [];
        if (is_array($fun)) {
            $obj = self::getController($fun[0]);
            $function = $fun[1];
            $res = $obj->$function(...$route);
        } else {
            $res = $fun(...$route);
        }
        Router::response($res);
    }

    /**
     * Set route handler for specified route with GET method
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     */
    public static function get(string $route, array|callable $fun): void
    {
        Router::route('GET', $route, $fun);
    }

    /**
     * Set route handler for specified route with POST method
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     */
    public static function post(string $route, array|callable $fun): void
    {
        Router::route('POST', $route, $fun);
    }

    /**
     * Set route handler for specified route with PUT method
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     */
    public static function put(string $route, array|callable $fun): void
    {
        Router::route('PUT', $route, $fun);
    }

    /**
     * Set route handler for specified route with DELETE method
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     */
    public static function delete(string $route, array|callable $fun): void
    {
        Router::route('DELETE', $route, $fun);
    }

    /**
     * Set route handler for specified route with any methods
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     */
    public static function any(string $route, array|callable $fun): void
    {
        Router::route('any', $route, $fun);
    }
}
