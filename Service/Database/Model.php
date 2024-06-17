<?php

namespace Kim\Database;

use Kim\Support\Arrayable;

abstract class M
{
    use Arrayable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static string $table;

    /**
     * The array of model attributes.
     *
     * @var array
     */
    protected array $data;

    /**
     * The id of model.
     *
     * @var mixed
     */
    protected mixed $id;

    /**
     * The table fields.
     *
     * @var string
     */
    protected static string $fields = '*';

    /**
     * The primary key for the model.
     */
    protected static string $primaryKey = 'id';

    public function __construct(...$attributes)
    {
        $this->id = $attributes[self::$primaryKey];
        $this->data = $attributes;
    }

    protected static function select(): string
    {
        return 'SELECT '.self::$fields.' FROM '.self::$table;
    }

    protected static function whereId(string|int $id): string
    {
        return ' WHERE `'.self::$primaryKey."` = '$id'";
    }

    public static function find(string|int $id): array
    {
        return DB::first(self::select().self::whereId($id));
    }

    public static function create(array $row): int|string
    {
        $keys = $values = [];
        foreach ($row as $key => $value) {
            $keys[] = "`$key`";
            $values[] = "'$value'";
        }
        $keys = implode(',', array_keys($keys));
        $values = implode(',', array_keys($values));
        if (DB::sql('INSERT INTO '.self::$table." ($keys) VALUES ($values)")) {
            return DB::core()->insert_id;
        } else {
            throw new \Exception(DB::core()->error);
        }
    }

    public function update(array $row): int|string
    {
        $values = [];
        foreach ($row as $key => $value) {
            $values[] = "`$key`='$value'";
        }
        $values = implode(',', array_keys($values));
        if (DB::sql('UPDATE '.self::$table." SET $values".self::whereId($this->id))) {
            return DB::core()->insert_id;
        } else {
            throw new \Exception(DB::core()->error);
        }
    }

    public function delete(): int|string|bool
    {
        if (DB::sql('DELETE FROM '.self::$table.self::whereId($this->id))) {
            return true;
        } else {
            throw new \Exception(DB::core()->error);
        }
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
