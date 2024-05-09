# Using Controller

You can pass the data to a controller's method

-----

## GET

Handles if there is `GET` request to the `/route` route and run the `login()` method of the `UserController::class`

```php
Router::get('/route', [UserController::class, 'login']);
```

- **first param:** route pattern
- **second param:** array of:
  - Controller class
  - Controller's method name

## POST

Handles if there is `POST` request to the `/route` route and run the `login()` method of the `UserController::class`

```php
Router::post('/route', [UserController::class, 'login']);
```

- **first param:** route pattern
- **second param:** array of:
  - Controller class
  - Controller's method name

## PUT

Handles if there is `PUT` request to the `/route` route and run the `login()` method of the `UserController::class`

```php
Router::put('/route', [UserController::class, 'login']);
```

- **first param:** route pattern
- **second param:** array of:
  - Controller class
  - Controller's method name

## DELETE

Handles if there is `DELETE` request to the `/route` route and run the `login()` method of the `UserController::class`

```php
Router::delete('/route', [UserController::class, 'login']);
```

- **first param:** route pattern
- **second param:** array of:
  - Controller class
  - Controller's method name

## Multiple

Handles if there is `GET` or `PUT` request to the `/route` route and run the `login()` method of the `UserController::class`

```php
Router::route(['GET', 'PUT'], '/route', [UserController::class, 'login']);
```

- **first param:** array of valid methods
- **second param:** route pattern
- **third param:** array of:
  - Controller class
  - Controller's method name

## Any

Handles if there is any type of request to the `/route` route and run the `login()` method of the `UserController::class`

```php
Router::any('/route', [UserController::class, 'login']);
```

- **first param:** route pattern
- **second param:** array of:
  - Controller class
  - Controller's method name
