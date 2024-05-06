<?php

namespace Kim\Support\Helpers;

class Config
{
    private static array $config;

    private static function buildConfig():void {
        self::$config = include __ROOT__.'/config.php';
    }

    public static function getConfig(string $key) : mixed {
        if(!isset(self::$config)) {
            self::buildConfig();
        }
        return array_key_exists($key, self::$config) ? self::$config[$key] : null;
    }
}
