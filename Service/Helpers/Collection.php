<?php

namespace Kim\Support\Helpers;

class Collection
{
    use Arrayable;

    /**
     * @var array The Collection's data
     */
    protected array $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function toArray(): array
    {
        return $this->collection;
    }

    public function keyBy(string|int $field): self
    {
        $this->collection = array_column($this->collection, null, $field);
        return $this;
    }

    public function getColumn(string|int $field): array
    {
        return array_column($this->collection, $field);
    }

    public function keys(): array
    {
        return array_keys($this->collection);
    }
}
