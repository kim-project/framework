<?php

define('__ROOT__', __DIR__);

require 'Service/Autoload.php';

if (php_sapi_name() == 'cli-server') {
    if (preg_match('/^\/public\//i', $_SERVER['REQUEST_URI'])) {
        if (file_exists('.'.$_SERVER['REQUEST_URI'])) {
            return false;
        } else {
            Response(404, 'Page not found');
        }
    }
}

loadEnv();

# \Kim\Support\Database\DB::connect();
\Kim\Service\Router\Server::startServer();
