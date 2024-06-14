# DB

- [Getting the connection](#getting-the-connection)
- [Running SQL](#running-sql)
- [Running Select Query](#running-select-query)
- [Getting first result](#getting-first-result)

## Getting the connection

You can access the connection with the following code

```php
$conn = DB::core();
```

Which will return `\PDO` connection by default and `\mysqli` connection if you set `MYSQLI` to `1`

## Running SQL

You can run sql queries by using the following command

```php
$conn = DB::sql("INSERT INTO Customers (CustomerName, City, Country) VALUES ('Cardinal', 'Stavanger', 'Norway')");
```

This will execute the sql query

## Running Select Query

You can run sql queries by using the following command

```php
$conn = DB::fetch(" SELECT CustomerName, City FROM Customers");
```

This will execute the sql query and retrieve the results in an array

## Getting first result

You can run sql queries by using the following command

```php
$conn = DB::first(" SELECT CustomerName, City FROM Customers");
```

This will execute the sql query and retrieve the result as an array
