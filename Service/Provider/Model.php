<?php

namespace Kim\Support\Provider;

abstract class Model
{
    abstract public function select(string $where): array;

    abstract public function find(string|int $id): array;

    abstract public function delete(string|int $id): int|string|bool;
}
