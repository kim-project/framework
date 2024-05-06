<?php

namespace Kim\Service;

class Autoload
{
    private const FILES = [
        'Service/Support.php',
        'Service/Env.php',
    ];

    private static function parseClassName(string $class): array
    {
        return array_values(
            array_filter(
                explode('\\', $class)
            )
        );
    }

    private static function getClass(string $class): ?string
    {
        $path = self::parseClassName($class);
        if ($path[0] == 'app') {
            return implode('/', $path).'.php';
        } elseif ($path[0] == 'Kim') {
            if ($path[1] == 'Service' || $path[1] == 'Support') {
                return 'Service/'.implode('/', array_slice($path, 2)).'.php';
            }

            return null;
        }

        return null;
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
