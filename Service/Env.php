<?php

use Kim\Support\Helpers\Config;

function loadEnv(): void
{
    $conf = parse_ini_file(__ROOT__.'/.env');
    foreach ($conf as $key => $val) {
        if(isset($val) && $val != '') {
            putenv("$key=$val");
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }
}

function env(string $key, mixed $default = null): mixed
{
    if(isset($_ENV[$key])) {
        return $_ENV[$key];
    } else {
        return $default;
    }
}

function config(string $key): mixed
{
    return Config::getConfig($key);
}
