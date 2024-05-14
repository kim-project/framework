<?php

namespace Kim\Service\Router;

use Kim\Service\Request\Request;

class Server
{
    /**
     * The request route
     *
     * @var string[]
     */
    private static array $route;

    /**
     * The set prefix
     *
     * @var array|false
     */
    private static array|bool $prefix = [];

    /**
     * The request
     *
     * @var Request
     */
    private static Request $request;

    /**
     * initialize the server
     *
     * @return void
     */
    private static function init(): void
    {
        //Parse Route

        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        self::$route = array_values(
            array_filter(
                explode('/', $route)
            )
        );

        //CSRF Handler

        if (! isset($_SESSION['csrf'])) {

            $_SESSION['csrf'] = md5(uniqid(mt_rand(), true));

        }

        define('CSRF', "<input type=\"hidden\" name=\"token\" value=\"{$_SESSION['csrf']}\">");

        //Request Handlers
        self::$request = new Request();

        $GLOBALS['_PUT'] = [];
        $GLOBALS['_DELETE'] = [];
        if(self::checkMethod('put')) {
            $GLOBALS['_PUT'] = Request::parseInput();
        } elseif (self::checkMethod('delete')) {
            $GLOBALS['_DELETE'] = Request::parseInput();
        }
    }

    /**
     * Handle errors
     *
     * @param \Throwable $th Error
     * @param bool $isapi Show error in json format
     *
     * @return void
     */
    private static function error(\Throwable $th, bool $isapi = false): void
    {
        $code = $th->getCode();

        if ($code < 400) {

            Response(503, $th->getMessage(), $isapi);

        } else {

            if (get_error_message($code) != null) {
                $code = 503;
            }
            Response($code, $th->getMessage(), $isapi);

        }

        exit();
    }

    /**
     * Start router files and route handling
     *
     * @return void
     */
    public static function startServer(): void
    {
        self::init();

        if (isset(self::$route[0]) && self::$route[0] == 'api') {

            try {

                self::setPrefix('/api/');
                require 'Routes/api.php';

            } catch (\Throwable $th) {

                self::error($th, true);

            }
            Response(404, 'route not found', true);

        } else {

            try {

                self::setPrefix('/');
                require 'Routes/web.php';

            } catch (\Throwable $th) {

                self::error($th);

            }
            Response(404, 'Page not found');

        }
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

        } elseif ($method == 'any') {

            return true;

        } else {

            return $_SERVER['REQUEST_METHOD'] == strtoupper($method);

        }
    }

    /**
     * check route match
     *
     * @param  string  $route  Route pattern to match
     * @param  bool  $exact  Check for full match or suffix match
     *
     * @return false|string[] returns array of route params
     */
    public static function checkRoute(string $route, bool $exact = true): bool|array
    {
        if (self::$prefix === false) {
            return false;
        }
        $route = array_values(
            array_filter(
                explode('/', $route)
            )
        );
        $request = array_values(
            array_diff_assoc(self::$route, self::$prefix)
        );

        $data = [
            'request' => self::$request
        ];

        if (count($route) > count($request)) {
            return false;
        }

        if ($exact && count($route) != count($request)) {
            return false;
        }

        foreach ($route as $key => $value) {

            if (substr($value, 0, 1) == ':') {

                $data[substr($value, 1)] = $request[$key];

            } elseif (strtolower($value) != strtolower($request[$key])) {

                return false;

            }

        }

        return $data;
    }

    /**
     * Get current request's route
     *
     * @return string
     */
    public static function getRoute(): string
    {
        return '/'.implode('/', self::$route);
    }

    /**
     * Set Prefix for match
     *
     * @param string $prefix
     *
     * @return void
     */
    public static function setPrefix(string $prefix): void
    {
        if (self::checkRoute($prefix, false) !== false) {

            self::$prefix = array_values(
                array_filter(
                    explode('/', $prefix)
                )
            );

        } else {

            self::$prefix = false;

        }
    }

    /**
     * Get Prefix for match
     *
     * @return string
     */
    public static function getPrefix(): string
    {
        return '/'.implode('/', self::$prefix);
    }
}
