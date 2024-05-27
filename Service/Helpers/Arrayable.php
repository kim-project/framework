<?php

namespace Kim\Support\Helpers;

trait Arrayable
{
    /**
     * convert object to array
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * convert object to Json
     *
     * @return string json encoded string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * get specified elements from array
     *
     * @return mixed|mixed[] element or array of elements
     */
    protected static function getOnly(array|string|int $only, array $array): mixed
    {
        if (is_array($only)) {
            return array_filter($array, function ($key) use ($only) {
                return in_array($key, $only);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            return array_key_exists($only, $array) ? $array[$only] : null;
        }
    }

    /**
     * get specified elements
     *
     * @return mixed|mixed[] element or array of elements
     */
    public function only(array|string|int $only): mixed
    {
        return self::getOnly($only, $this->toArray());
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
