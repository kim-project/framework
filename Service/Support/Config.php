<?php

namespace Kim\Support;

class Config
{
    /**
     * @var array Configurations
     */
    private static array $config;

    /**
     * Get the Configurations
     *
     * @return void
     */
    private static function buildConfig(): void
    {
        self::$config = include __ROOT__.'/config.php';
    }

    /**
     * Get Specific Config
     *
     * @param string $key The Key of config to get
     *
     * @return mixed The Value of the config
     */
    public static function getConfig(string $key): mixed
    {
        if(!isset(self::$config)) {
            self::buildConfig();
        }
        return array_key_exists($key, self::$config) ? self::$config[$key] : null;
    }
}
