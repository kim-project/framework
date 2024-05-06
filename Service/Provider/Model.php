<?php

namespace Kim\Support\Provider;

abstract class Model
{
    /**
     * Select query on the table
     *
     * @param string $where the search query
     *
     * @return array[] Results
     */
    abstract public function select(string $where): array;

    /**
     * Select query on the table
     *
     * @param string $where the search query
     *
     * @return array The first reslut
     */
    abstract public function first(string $where): array;

    /**
     * Get model by id
     *
     * @param string|int $id ID of the model
     *
     * @return array The model
     */
    abstract public function find(string|int $id): array;

    /**
     * Delete model by id
     *
     * @param string|int $id ID of the model
     *
     * @return bool If the model was deleted
     */
    abstract public function delete(string|int $id): bool;
}
