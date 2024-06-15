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

    /**
     * Converts the collection to array
     *
     * @return array the collection to array
     */
    public function toArray(): array
    {
        return $this->collection;
    }

    /**
     * Key the array by a specific field of the elements
     *
     * @return Collection returns the updated collection
     */
    public function keyBy(string|int $field): self
    {
        $this->collection = array_column($this->collection, null, $field);
        return $this;
    }

    /**
     * reverse the keys and elements
     *
     * @return Collection returns the updated collection
     */
    public function flip(): self
    {
        $this->collection = array_flip($this->collection);
        return $this;
    }

    /**
     * Get specific column of the elements only
     *
     * @return array returns the specific field in the elements
     */
    public function getColumn(string|int $field): array
    {
        return array_column($this->collection, $field);
    }

    /**
     * Get the keys of the elements as an array
     *
     * @return array keys of the array
     */
    public function keys(): array
    {
        return array_keys($this->collection);
    }
}
