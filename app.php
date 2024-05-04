<?php

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
/*
$DBInfo = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'password',
    'database' => 'Kargah'
];
\Kim\Support\Helpers\DB::connect(...$DBInfo);
*/
\Kim\Service\Router\Server::startServer();
