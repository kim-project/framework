<?php

namespace Kim\Service;

class Autoload
{
    private const FILES = [
        'Service/Support.php',
        'Service/Env.php',
    ];

    private static array $autoload = [
        'Psr\\Http\\Server\\' => 'Service/Psr/Server/',
        'Psr\\' => 'Service/Psr/',
        'Kim\\' => 'Service/',
    ];

    private static function getClass(string $class)
    {
        foreach (self::$autoload as $namespace => $dir) {
            if(str_starts_with($class, $namespace)) {
                $file = $dir.substr($class, strlen($namespace)).'.php';
                if (file_exists($file)) {
                    return $file;
                }
            }
        }
        if (file_exists($class.'.php')) {
            return $class.'.php';
        }
        return;
    }

    public static function register(): void
    {
        spl_autoload_register(function (string $class) {
            if ($path = self::getClass($class)) {
                require_once ROOT.$path;
            }
        });

        foreach (self::FILES as $value) {
            require_once ROOT.$value;
        }
    }
}

define('ROOT', __DIR__.'/../');

Autoload::register();
