<?php

namespace Kim\Support\Database;

class DB
{
    /**
     * The database connection
     *
     * @var \mysqli|\PDO
     */
    private static \mysqli|\PDO $connection;

    /**
     * Create connection to database
     *
     * @param  string  $host  The path in which the file should be created
     * @param  string  $username  The content to put in the file
     * @param  string  $password  The content to put in the file
     * @param  string  $database  The content to put in the file
     *
     * @return void
     */
    public static function connect(): void
    {
        if (isset(DB::$connection)) {
            throw new \Exception('Connection Exists.');
        }
        $host = config('db_host');
        $username = config('db_username');
        $password = config('db_password');
        $database = config('db_database');
        if(config('db_mysqli')) {
            $port = env('DB_PORT', 3306);
            DB::$connection = new \mysqli($host, $username, $password, $database, $port);
            if (DB::$connection->connect_error) {
                exit('Connection failed: '.DB::$connection->connect_error);
            }
        } else {
            try {
                switch (config('db_type')) {
                    case 'sqlite':
                        DB::$connection = new \PDO("sqlite:".config('sqlite_path'));
                        break;

                    case 'pgsql':
                        $port = env('DB_PORT', 5432);
                        DB::$connection = new \PDO("pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password");
                        break;

                    case 'mysql':
                        $port = env('DB_PORT', 3306);
                        DB::$connection = new \PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);

                        // no break
                    case 'sqlsrv':
                        $port = env('DB_PORT', 1433);
                        DB::$connection = new \PDO("sqlsrv:Server=$host,$port;Database=$database", $username, $password);

                }
                // set the PDO error mode to exception
                DB::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch(\PDOException $e) {
                exit('Connection failed: '.$e->getMessage());
            }
        }
    }

    /**
     * Get the connection
     *
     * @return \mysqli
     */
    public static function core(): \mysqli|\PDO
    {
        return DB::$connection;
    }

    /**
     * Close the connection
     *
     * @return bool
     */
    public static function close(): bool
    {
        if (isset(DB::$connection) && DB::$connection instanceof \mysqli) {
            return DB::$connection->close();
        } else {
            return false;
        }
    }

    /**
     * Run the sql and return the results
     *
     * @param  string  $sql  The sql query to run
     *
     * @return bool|\mysqli_result|int
     */
    public static function sql(string $sql): bool|\mysqli_result|int
    {
        if(DB::$connection instanceof \PDO) {
            try {
                return DB::$connection->exec($sql);
            } catch (\Throwable $th) {
                response(503, $th->getMessage());
                return false;
            }
        } else {
            return DB::$connection->query($sql);
        }
    }

    /**
     * Run the sql (SELECT query) and return the results in an associative array
     *
     * @param  string  $sql  The sql query to run
     *
     * @return array[]
     */
    public static function fetch(string $sql): array
    {
        $response = [];
        if(DB::$connection instanceof \PDO) {
            try {
                $stm = DB::$connection->query($sql);
                $response = $stm->fetchAll(\PDO::FETCH_ASSOC);
            } catch (\Throwable $th) {
                response(503, $th->getMessage());
            }
        } else {
            $result = DB::$connection->query($sql);
            $response = $result->fetch_all();
            $result->free_result();
        }

        return $response;
    }

    /**
     * Run the sql (SELECT query) and return the first result in an associative array
     *
     * @param  string  $sql  The sql query to run
     *
     * @return array
     */
    public static function first(string $sql): array
    {
        $response = [];
        if(DB::$connection instanceof \PDO) {
            try {
                $stm = DB::$connection->query($sql);
                $response = $stm->fetch(\PDO::FETCH_ASSOC);
            } catch (\Throwable $th) {
                response(503, $th->getMessage());
            }
        } else {
            $result = DB::$connection->query($sql);
            $response = $result->fetch_assoc();
            $result->free_result();
        }

        return $response;
    }
}
