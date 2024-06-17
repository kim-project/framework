<?php

namespace Kim\Support;

trait Singleton
{
    /**
     * @var object The instance of the singleton
     */
    protected static object $instance;

    protected function __construct()
    {
    }

    private function __clone(): void
    {
    }

    final public function __wakeup(): void
    {
    }

    /**
     * Get the singleton's instance
     *
     * @return self The instance
     */
    public static function getInstance(): self
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static();
    }
}
