<?php

namespace Kim\Core;

use Exception;
use Kim\Provider\Singleton;
use Psr\Container\ContainerInterface;
use ReflectionNamedType;

class Container implements ContainerInterface
{
    use Singleton;

    private array $instances = [];

    public function get(string $id)
    {
        if ($id == self::class) {
            return self::getInstance();
        }
        if ($this->has($id)) {
            return $this->instances[$id];
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        if ($id == self::class) {
            return true;
        }
        return array_key_exists($id, $this->instances);
    }

    private function resolve(string $id)
    {
        $class = new \ReflectionClass($id);
        $params = $this->autowire($class->getConstructor());

        if (in_array(Singleton::class, $class->getTraitNames())) {
            $this->instances[$id] = $id::getInstance(...$params);
        } else {
            $this->instances[$id] = $class->newInstanceArgs($params);
        }

        return $this->instances[$id];
    }

    public function autowire(\ReflectionFunctionAbstract $function, array $data = []): array
    {
        $params = [];
        foreach ($function->getParameters() as $param) {
            if (array_key_exists($param->name, $data)) {
                $params[$param->name] = $data[$param->name];
                continue;
            }

            if ($param->isOptional()) {
                continue;
            }

            $type = $param->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $params[$param->name] = $this->get($type->getName());
            } else {
                throw new Exception();
            }
        }

        return $params;
    }
}
