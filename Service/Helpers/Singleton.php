<?php

namespace Kim\Support\Helpers;

trait Singleton
{
    protected static $instance;

    protected function __construct()
    {
    }

    private function __clone(): void
    {
    }

    final public function __wakeup(): void
    {
    }

    public static function getInstance(): self
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static();
    }
}
