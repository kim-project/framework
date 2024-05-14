# Basic Requests

- [Introduction](#introduction)
- [Methods](#methods)
  - [GET](#get)
  - [POST](#post)
  - [PUT](#put)
  - [DELETE](#delete)
  - [Multiple](#multiple)
  - [Any](#any)

## Introduction

These routers handles requests by passing a function

-----

## Methods

### GET

Handles if there is `GET` request to the `/route` route and runs the given function

```php
Router::get('/route', function () {
    ...
});
```

- **first param:** route pattern
- **second param:** function to run

### POST

Handles if there is `POST` request to the `/route` route and runs the given function

```php
Router::post('/route', function () {
    ...
});
```

- **first param:** route pattern
- **second param:** function to run

### PUT

Handles if there is `PUT` request to the `/route` route and runs the given function

```php
Router::put('/route', function () {
    ...
});
```

- **first param:** route pattern
- **second param:** function to run

### DELETE

Handles if there is `DELETE` request to the `/route` route and runs the given function

```php
Router::delete('/route', function () {
    ...
});
```

- **first param:** route pattern
- **second param:** function to run

### Multiple

Handles if there is `GET` or `PUT` request to the `/route` route and runs the given function

```php
Router::route(['GET', 'PUT'], '/route', function () {
    ...
});
```

- **first param:** array of valid methods
- **second param:** route pattern
- **third param:** function to run

### Any

Handles if there is any type of request to the `/route` route and runs the given function

```php
Router::any('/route', function () {
    ...
});
```

- **first param:** route pattern
- **second param:** function to run
