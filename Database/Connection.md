# Connection

- [Connection Config](#connection-config)
  - [MySQL / MariaDB](#mysql--mariadb)
  - [PostgreSQL](#postgresql)
  - [Microsoft SQL Server](#microsoft-sql-server)
  - [SQLite](#sqlite)

## Connection Config

You can set your database connection config in the `.env` file in the project root
  
It should be like this

```ini
MYSQLI=1
DB_TYPE=mysql
DB_HOST=127.0.0.1
DB_PORT=
DB_USER=root
DB_PASS=
DB_DATABASE=
```

### MySQL / MariaDB

```ini
MYSQLI=1
DB_TYPE=mysql
DB_HOST=127.0.0.1
DB_PORT=
DB_USER=root
DB_PASS=
DB_DATABASE=Kim
```

Here's the description for the fields (set empty for default value)

- `MYSQLI`: Defines if you want to use `MYSQLI` or `PDO` connection. default is `0` Which is `PDO` connection (`MYSQLI` is only available for MySQL or MariaDB)
- `DB_TYPE`: The Database type (`mysql` value for MySQL and MariaDB)
- `DB_HOST`: The Host address for the database (default value: `localhost`)
- `DB_PORT`: The Port for the database (default value: `3306` for MySQL databases)
- `DB_USER`: The Username for database connection (default value: `root`)
- `DB_PASS`: The Password for database connection
- `DB_DATABASE`: DB name of the database to use (default value: `Kim`)

### PostgreSQL

```ini
DB_TYPE=pgsql
DB_HOST=127.0.0.1
DB_PORT=
DB_USER=root
DB_PASS=
DB_DATABASE=Kim
```

Here's the description for the fields (set empty for default value)

- `DB_TYPE`: The Database type (`pgsql` value for PostgreSQL)
- `DB_HOST`: The Host address for the database (default value: `localhost`)
- `DB_PORT`: The Port for the database (default value: `5432` for PostgreSQL databases)
- `DB_USER`: The Username for database connection (default value: `root`)
- `DB_PASS`: The Password for database connection
- `DB_DATABASE`: DB name of the database to use (default value: `Kim`)

### Microsoft SQL Server

```ini
DB_TYPE=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=
DB_USER=root
DB_PASS=
DB_DATABASE=Kim
```

Here's the description for the fields (set empty for default value)

- `DB_TYPE`: The Database type (`sqlsrv` value for Microsoft SQL Server)
- `DB_HOST`: The Host address for the database (default value: `localhost`)
- `DB_PORT`: The Port for the database (default value: `1433` for Microsoft SQL Server databases)
- `DB_USER`: The Username for database connection (default value: `root`)
- `DB_PASS`: The Password for database connection
- `DB_DATABASE`: DB name of the database to use (default value: `Kim`)

### SQLite

```ini
DB_TYPE=sqlite
DB_PATH=/database/database.sqlite
```

Here's the description for the fields (set empty for default value)

- `DB_TYPE`: The Database type (`sqlite` value for SQLite)
- `DB_PATH`: The location of SQLite database file (default value: `/database/database.sqlite`)
