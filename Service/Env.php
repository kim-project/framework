<?php

use Kim\Support\Helpers\Config;

/**
 * Load the .env file
 *
 * @return void
 */
function loadEnv(): void
{
    if (!file_exists(__ROOT__.'/.env')) {
        return;
    }
    $conf = parse_ini_file(__ROOT__.'/.env');
    foreach ($conf as $key => $val) {
        if(isset($val) && $val !== '') {
            putenv("$key=$val");
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }
}

/**
 * Get env value
 *
 * @param string $key The env value to get
 * @param mixed $default The default value if the env was empty
 *
 * @return mixed The value
 */
function env(string $key, mixed $default = null): mixed
{
    if(isset($_ENV[$key])) {
        return $_ENV[$key];
    } else {
        return $default;
    }
}

/**
 * Get config value
 *
 * @param string $key The config value to get
 *
 * @return mixed The value
 */
function config(string $key): mixed
{
    return Config::getConfig($key);
}

loadEnv();
