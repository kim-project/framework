<?php

namespace app\Models;

use Kim\Support\Database\DB;
use Kim\Support\Provider\Model;

class User extends Model
{
    public static function select(string $where): array
    {
        return DB::fetch("SELECT * FROM user $where");
    }

    public static function find(string|int $id): array
    {
        return DB::first("SELECT * FROM user WHERE `id`='$id'");
    }

    public static function first(string $where): array
    {
        return DB::first("SELECT * FROM user $where");
    }

    public static function delete(string|int $id): bool
    {
        return DB::sql("DELETE FROM user WHERE `id`='$id'");
    }
}
