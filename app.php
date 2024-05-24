<?php

#_________ ______________  ___
# _____  //_/___  _/__   |/  /
#  ___  ,<   __  / __  /|_/ /
#   _  /| | __/ /  _  /  / /
#   /_/ |_| /___/  /_/  /_/


define('__ROOT__', __DIR__);

require 'Service/Autoload.php';

if (php_sapi_name() === 'cli-server') {
    if (preg_match('/^\/public\//i', $_SERVER['REQUEST_URI'])) {
        if (file_exists('.'.$_SERVER['REQUEST_URI'])) {
            return false;
        } else {
            Response(404, 'Page not found');
        }
    }
}

loadEnv();

\Kim\Service\Router\Server::startServer();
