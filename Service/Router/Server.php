<?php

namespace Kim\Service\Router;

use Kim\Service\Request\Request;
use Kim\Support\Helpers\Singleton;

class Server
{
    use Singleton {getInstance as getServer;}

    /**
     * The request route
     *
     * @var string[]
     */
    private array $route;

    /**
     * initialize the server
     */
    protected function __construct()
    {
        //Parse Route

        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $this->route = array_values(
            array_filter(
                explode('/', $route)
            )
        );

        //CSRF Handler

        if (! isset($_SESSION['csrf'])) {

            $_SESSION['csrf'] = bin2hex(random_bytes(32));

        }

        define('CSRF', "<input type=\"hidden\" name=\"token\" value=\"{$_SESSION['csrf']}\">");

        //Request Handlers
        $GLOBALS['_PUT'] = [];
        $GLOBALS['_DELETE'] = [];
        if(self::checkMethod('put')) {
            $GLOBALS['_PUT'] = Request::parseInput();
        } elseif (self::checkMethod('delete')) {
            $GLOBALS['_DELETE'] = Request::parseInput();
        }

        define('IS_API', isset($this->route[0]) && $this->route[0] === 'api');
    }

    /**
     * Start router files and route handling
     *
     * @return void
     */
    public function start(): void
    {
        session_start();

        try {

            if (IS_API) {
                require 'Routes/api.php';
            } else {
                require 'Routes/web.php';
            }

        } catch (\Throwable $th) {

            response(500, $th->getMessage());
            exit;

        }
        response(404, 'Page not found');
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
     * @return false|array returns array of route params
     */
    public function checkRoute(string $route, bool $exact = true): bool|array
    {
        $route = array_values(
            array_filter(
                explode('/', $route)
            )
        );
        if (IS_API) {
            array_unshift($route, 'api');
        }

        $data = [
            'request' => Request::getRequest()
        ];

        if (count($route) > count($this->route)) {
            return false;
        }

        if ($exact && count($route) !== count($this->route)) {
            return false;
        }

        foreach ($route as $key => $value) {

            if (substr($value, 0, 1) === ':') {

                $data[substr($value, 1)] = $this->route[$key];

            } elseif (strtolower($value) !== strtolower($this->route[$key])) {

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
    public function getRoute(): string
    {
        return '/'.implode('/', $this->route);
    }
}
