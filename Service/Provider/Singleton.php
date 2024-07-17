<?php

namespace Kim\Provider;

trait Singleton
{
    /**
     * @var object The instance of the singleton
     */
    protected static ?self $instance = null;

    /**
     * is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead
     */
    private function __construct()
    {
    }

    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone()
    {
    }

    /**
     * prevent from being unserialized (which would create a second instance of it)
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * Get the singleton's instance
     *
     * @return self The instance
     */
    public static function getInstance(...$args): self
    {
        if (self::$instance === null) {
            self::$instance = new self(...$args);
        }

        return self::$instance;
    }
}
