<?php

namespace app\Models;

use Kim\Support\Database\DB;

class User
{
    public static function select(string $where): array
    {
        return DB::fetch("SELECT * FROM user $where");
    }

    public static function find(string|int $id): array|null
    {
        return DB::first("SELECT * FROM user WHERE id='$id'");
    }

    public static function first(string $where): array|null
    {
        return DB::first("SELECT * FROM user $where");
    }

    public static function delete(string|int $id): bool
    {
        return DB::sql("DELETE FROM user WHERE id='$id'");
    }
}
