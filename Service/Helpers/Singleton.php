<?php

namespace Kim\Support\Helpers;

trait Singleton
{
    protected static $instance;

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    final public function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static();
    }
}
