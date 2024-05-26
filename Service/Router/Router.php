<?php

namespace Kim\Service\Router;

use Kim\Support\Helpers\Response;
use Kim\Support\Provider\Controller;

class Router
{
    private static function parseParam(\ReflectionMethod|\ReflectionFunction $f, array $data): array
    {
        $result = array();
        foreach ($f->getParameters() as $param) {
            $result[$param->name] = $data[$param->name];
        }
        return $result;
    }

    /**
     * Response data
     *
     * @param  mixed  $response  Data to response
     *
     * @return void
     */
    private static function response(mixed $response): Response
    {
        if ($response instanceof Response) {
            return $response;
        } elseif (is_array($response) || is_object($response)) {
            return new Response(200, $response);
        } else {
            return response()->string($response);
        }
    }

    /**
     * create a controller instance
     *
     * @param  string  $class  The Controller class
     *
     * @return Controller
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
     *
     * @return void
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
            $res = $obj->$function(...self::parseParam(new \ReflectionMethod($obj, $function), $route));
            self::response($res)();
        }
    }

    /**
     * Set route handler for specified route and method(s)
     *
     * @param  array|string  $method  string or an array of valid method(s)
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     *
     * @return void
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
            $res = $obj->$function(...self::parseParam(new \ReflectionMethod($obj, $function), $route));
        } else {
            $res = $fun(...self::parseParam(new \ReflectionFunction($fun), $route));
        }
        self::response($res)();
    }

    /**
     * Set route handler for specified route with GET method
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     *
     * @return void
     */
    public static function get(string $route, array|callable $fun): void
    {
        self::route('GET', $route, $fun);
    }

    /**
     * Set route handler for specified route with POST method
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     *
     * @return void
     */
    public static function post(string $route, array|callable $fun): void
    {
        self::route('POST', $route, $fun);
    }

    /**
     * Set route handler for specified route with PUT method
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     *
     * @return void
     */
    public static function put(string $route, array|callable $fun): void
    {
        self::route('PUT', $route, $fun);
    }

    /**
     * Set route handler for specified route with DELETE method
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     *
     * @return void
     */
    public static function delete(string $route, array|callable $fun): void
    {
        self::route('DELETE', $route, $fun);
    }

    /**
     * Set route handler for specified route with any methods
     *
     * @param  string  $route  The route to handle
     * @param  array|callable  $fun  A callable function or an array of a controller class and a function name
     *
     * @return void
     */
    public static function any(string $route, array|callable $fun): void
    {
        self::route('any', $route, $fun);
    }
}
