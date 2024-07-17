<?php

namespace Kim\Router;

use Kim\Core\Container;
use Kim\Request\Request;
use Kim\Support\Response;
use Kim\Provider\Singleton;

class Router
{
    use Singleton;

    /**
     * @var array The dispatch info
     */
    private array $dispatch = [];

    /**
     * @var string[] The request route
     */
    private static array $route;

    /**
     * @var int The route prefix
     */
    private static int $prefix = 0;

    protected function __construct(private Request $request)
    {
        self::$route = self::normalizeRoute($request->route);
        define('IS_API', Router::checkRoute('/api', false));
        define('ROUTE_CACHE', file_exists('./cache/routes.json'));
        if (ROUTE_CACHE) {
            RouteCache::load();
            $dis = RouteCache::match($request->method, self::$route);
            if ($dis !== false) {
                $this->dispatch = $dis;
                $this->dispatch();
            }
        }
    }

    /**
     * returns array of route sections
     *
     * @param  string  $pattern  the pattern to normalize
     *
     * @return array
     */
    private static function normalizeRoute(string $pattern): array
    {
        return array_values(array_filter(
            explode('/', strtolower($pattern))
        ));
    }

    /**
     * check method match
     *
     * @param  string|array  $method  String or array of method(s)
     *
     * @return bool
     */
    public static function checkMethod(string|array $method): bool
    {
        if (is_array($method)) {
            return in_array($_SERVER['REQUEST_METHOD'], $method);
        } elseif ($method === 'any') {
            return true;
        } else {
            return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
        }
    }

    /**
     * check route match
     *
     * @param  string  $route  Route pattern to match
     * @param  bool  $exact  Check for full match or suffix match
     *
     * @return boolean|array returns array of route params
     */
    public static function checkRoute(string|array $route, bool $exact = true): bool|array
    {
        if (!is_array($route)) {
            $route = self::normalizeRoute($route);
        }
        if ($exact && count($route) + self::$prefix !== count(self::$route)) {
            return false;
        } elseif (count($route) + self::$prefix > count(self::$route)) {
            return false;
        }
        $data = [];

        foreach ($route as $key => $value) {
            if (substr($value, 0, 1) === ':') {
                if ($exact) {
                    $data[substr($value, 1)] = self::$route[$key + self::$prefix];
                }
            } elseif ($value !== self::$route[$key + self::$prefix]) {
                return false;
            }
        }
        return $exact ? $data : true;
    }

    private function dispatch(): void
    {
        $res = [];
        if ($this->dispatch == []) {
            response(404, 'Page not found')();
        }
        $container = Container::getInstance();
        if (is_array($this->dispatch['call'])) {
            $obj = $container->get($this->dispatch['call'][0]);
            $function = $this->dispatch['call'][1];
            $res = $obj->$function(...$container->autowire(new \ReflectionMethod($obj, $function), $this->dispatch['params']));
        } else {
            $res = $this->dispatch['call'](...$container->autowire(new \ReflectionFunction($this->dispatch['call']), $this->dispatch['params']));
        }
        $this->response($res)();
    }

    private function parseParam(\ReflectionFunctionAbstract $f, array $data): array
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
    private function response(mixed $response): Response
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
        if (! isset($_SERVER['CACHING_ROUTES'])) {
            if (ROUTE_CACHE) {
                return;
            }
            if (self::checkRoute($prefix, false) === false) {
                return;
            }
        }


        foreach (array_filter($routes) as $value) {
            self::route($value['method'], $prefix.'/'.$value['route'], [$class, $value['function']]);
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
        if (isset($_SERVER['CACHING_ROUTES'])) {
            RouteCache::addRoute($method, self::normalizeRoute($route), $fun);
            return;
        }
        if (ROUTE_CACHE && is_array($fun)) {
            return;
        }
        if (! self::checkMethod($method)) {
            return;
        }
        $route = self::checkRoute($route);
        if ($route === false) {
            return;
        }
        $router = Router::getInstance();
        $router->dispatch = [
            'call' => $fun,
            'params' => $route
        ];

        $router->dispatch();
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

    public static function setPrefix(string $prefix): bool
    {
        $prefix = self::normalizeRoute($prefix);
        if (Router::checkRoute($prefix, false) === false) {
            return false;
        } else {
            self::$prefix = count($prefix);
            return true;
        }
    }
}
