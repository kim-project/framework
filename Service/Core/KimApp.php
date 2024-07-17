<?php

namespace Kim\Core;

use Kim\Request\Request;
use Kim\Provider\Singleton;
use Kim\Router\RouteCache;
use Kim\Router\Router;

class KimApp
{
    use Singleton{getInstance as create;}

    /**
     * @var string[] The request route
     */
    private array $route;

    /**
     * @var Request The request instance
     */
    private Request $request;

    /**
     * initialize the server
     */
    protected function __construct()
    {
        if (isset($_SERVER['CACHING_ROUTES'])) {
            return;
        }
        session_start();
        $container = Container::getInstance();
        $container->get(Router::class);
        //CSRF Handler
        if (! isset($_SESSION['csrf'])) {

            $_SESSION['csrf'] = bin2hex(random_bytes(32));

        }
        //Request Handlers
        $GLOBALS['_PUT'] = [];
        $GLOBALS['_DELETE'] = [];

        define('CSRF', "<input type=\"hidden\" name=\"token\" value=\"{$_SESSION['csrf']}\">");
    }

    public function routes(string $prefix, string $dir): void
    {
        if (isset($_SERVER['CACHING_ROUTES'])) {
            RouteCache::cacheRoutes($prefix, $dir);
            return;
        }
        if (! Router::setPrefix($prefix)) {
            return;
        }

        try {
            require $dir;
        } catch (\Throwable $th) {
            response(500, $th->getMessage());
        }
        response(404, 'Page not found')();
    }

}
