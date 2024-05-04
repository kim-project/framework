<?php

namespace Kim\Service\Router;

class Server
{
    /**
     * The request route
     */
    private static array $route;

    /**
     * The set prefix
     *
     * @var array
     */
    private static array|bool $prefix = [];

    /**
     * initialize the server
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
    }

    /**
     * Handle errors
     */
    private static function error(\Throwable $th, bool $isapi = false): void
    {
        $code = $th->getCode();

        if ($code < 400) {

            fwrite(STDERR, "\n".$th->getMessage());
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
     * @return bool|array returns array of route params
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
        $data = [];

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
     */
    public static function getRoute(): string
    {
        return '/'.implode('/', array_diff_assoc(self::$route, self::$prefix));
    }

    /**
     * Set Prefix for match
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
     */
    public static function getPrefix(): string
    {
        return '/'.implode('/', self::$prefix);
    }
}
