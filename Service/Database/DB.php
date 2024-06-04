<?php

namespace Kim\Support\Database;

class DB
{
    /**
     * @var \mysqli|\PDO The database connection
     */
    private static \mysqli|\PDO $connection;

    public static function connect(): void
    {

    }

    /**
     * Create connection to database
     *
     * @return \mysqli|\PDO Database connection
     */
    private static function conn(): \mysqli|\PDO
    {
        if (isset(self::$connection)) {
            return self::$connection;
        }
        $host = config('db_host');
        $username = config('db_username');
        $password = config('db_password');
        $database = config('db_database');
        if(config('db_mysqli')) {
            $port = env('DB_PORT', 3306);
            self::$connection = new \mysqli($host, $username, $password, $database, $port);
            if (self::$connection->connect_error) {
                exit('Connection failed: '.self::$connection->connect_error);
            }
        } else {
            try {
                switch (config('db_type')) {
                    case 'sqlite':
                        self::$connection = new \PDO('sqlite:'.config('sqlite_path'));
                        break;

                    case 'pgsql':
                        $port = env('DB_PORT', 5432);
                        self::$connection = new \PDO("pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password");
                        break;

                    case 'mysql':
                        $port = env('DB_PORT', 3306);
                        self::$connection = new \PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
                        break;

                    case 'sqlsrv':
                        $port = env('DB_PORT', 1433);
                        self::$connection = new \PDO("sqlsrv:Server=$host,$port;Database=$database", $username, $password);
                        break;

                }
                self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch(\PDOException $e) {
                exit('Connection failed: '.$e->getMessage());
            }
        }
        return self::$connection;
    }

    /**
     * Get the connection
     *
     * @return \mysqli|\PDO Database connection
     */
    public static function core(): \mysqli|\PDO
    {
        return self::conn();
    }

    /**
     * Close the connection
     *
     * @return bool
     */
    public static function close(): bool
    {
        $conn = isset(self::$connection) ? self::$connection : null;
        if ($conn instanceof \mysqli) {
            return $conn->close();
        } else {
            return true;
        }
    }

    /**
     * Run the sql and return the results
     *
     * @param  string  $sql  The sql query to run
     *
     * @return bool|\mysqli_result|int The execution result
     */
    public static function sql(string $sql): bool|\mysqli_result|int
    {
        $conn = self::conn();
        if($conn instanceof \PDO) {
            try {
                return $conn->exec($sql);
            } catch (\Throwable $th) {
                response(503, $th->getMessage());
                return false;
            }
        } else {
            return $conn->query($sql);
        }
    }

    /**
     * Run the sql (SELECT query) and return the results in an associative array
     *
     * @param  string  $sql  The sql query to run
     *
     * @return array[] The query result
     */
    public static function fetch(string $sql): array
    {
        $conn = self::conn();
        $response = [];
        if($conn instanceof \PDO) {
            try {
                $stm = $conn->query($sql);
                $response = $stm->fetchAll(\PDO::FETCH_ASSOC);
            } catch (\Throwable $th) {
                response(503, $th->getMessage());
            }
        } else {
            $result = $conn->query($sql);
            $response = $result->fetch_all(MYSQLI_ASSOC);
            $result->free_result();
        }

        return $response;
    }

    /**
     * Run the sql (SELECT query) and return the first result in an associative array
     *
     * @param  string  $sql  The sql query to run
     *
     * @return array|null The first result of query
     */
    public static function first(string $sql): array|null
    {
        $conn = self::conn();
        $response = [];
        if($conn instanceof \PDO) {
            try {
                $stm = $conn->query($sql);
                $response = $stm->fetch(\PDO::FETCH_ASSOC);
            } catch (\Throwable $th) {
                response(503, $th->getMessage());
            }
        } else {
            $result = $conn->query($sql);
            $response = $result->fetch_assoc();
            $result->free_result();
        }

        return $response;
    }
}
