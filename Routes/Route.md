# Route

- [Introduction](#introduction)
- [Methods](#methods)
  - [GET](#get)
  - [POST](#post)
  - [PUT](#put)
  - [DELETE](#delete)
  - [Multiple](#multiple)
  - [Any](#any)

## Introduction

These methods are for the `Router`'s Controller group

-----

## Methods

### GET

Handles if there is `GET` request to the `/suffix/route` route and run the `getLogin()` method of the controller class

```php
Route::get('/route', 'getLogin');
```

- **first param:** route pattern
- **second param:** name of the controller method to call

### POST

Handles if there is `POST` request to the `/suffix/route` route and run the `getLogin()` method of the controller class

```php
Route::post('/route', 'getLogin');
```

- **first param:** route pattern
- **second param:** name of the controller method to call

### PUT

Handles if there is `PUT` request to the `/suffix/route` route and run the `getLogin()` method of the controller class

```php
Route::put('/route', 'getLogin');
```

- **first param:** route pattern
- **second param:** name of the controller method to call

### DELETE

Handles if there is `DELETE` request to the `/suffix/route` route and run the `getLogin()` method of the controller class

```php
Route::delete('/route', 'getLogin');
```

- **first param:** route pattern
- **second param:** name of the controller method to call

### Multiple

Handles if there is `GET` or `PUT` request to the `/suffix/route` route and run the `getLogin()` method of the controller class

```php
Route::route(['GET', 'PUT'], '/route', 'getLogin');
```

- **first param:** array of valid methods
- **second param:** route pattern
- **third param:** name of the controller method to call

### Any

Handles if there is any type of request to the `/suffix/route` route and run the `getLogin()` method of the controller class

```php
Route::any('/route', 'getLogin');
```

- **first param:** route pattern
- **second param:** name of the controller method to call
