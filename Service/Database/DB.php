<?php

namespace Kim\Support\Database;

class DB
{
    /**
     * The database connection
     */
    private static \mysqli $connection;

    /**
     * Create connection to database
     *
     * @param  string  $host  The path in which the file should be created
     * @param  string  $username  The content to put in the file
     * @param  string  $password  The content to put in the file
     * @param  string  $database  The content to put in the file
     */
    public static function connect(string $host, string $username, string $password, string $database): void
    {
        if (DB::$connection) {
            throw new \Exception('Connection Exists.', 503);
        }
        DB::$connection = new \mysqli($host, $username, $password, $database);
        if (DB::$connection->connect_error) {
            exit('Connection failed: '.DB::$connection->connect_error);
        }
    }

    /**
     * Get the connection
     */
    public static function core(): \mysqli
    {
        return DB::$connection;
    }

    /**
     * Close the connection
     */
    public static function close(): bool
    {
        if (isset(DB::$connection)) {
            return DB::$connection->close();
        } else {
            return false;
        }
    }

    /**
     * Run the sql and return the results
     *
     * @param  string  $sql  The sql query to run
     */
    public static function sql(string $sql): bool|\mysqli_result
    {
        return DB::$connection->query($sql);
    }

    /**
     * Run the sql (SELECT query) and return the results in an associative array
     *
     * @param  string  $sql  The sql query to run
     */
    public static function fetch(string $sql): array
    {
        $result = DB::$connection->query($sql);
        $response = $result->fetch_all();
        $result->free_result();

        return $response;
    }

    /**
     * Run the sql (SELECT query) and return the first result in an associative array
     *
     * @param  string  $sql  The sql query to run
     */
    public static function first(string $sql): array
    {
        $result = DB::$connection->query($sql);
        $response = $result->fetch_assoc();
        $result->free_result();

        return $response;
    }
}
