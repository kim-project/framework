<?php

namespace Kim\Support\Helpers;

abstract class Arrayable
{
    /**
     * convert object to array
     */
    abstract public function toArray(): array;

    /**
     * convert object to Json
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

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

    public function only(array|string|int $only): mixed
    {
        return self::getOnly($only, $this->toArray());
    }
}
