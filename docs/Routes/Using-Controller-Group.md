# Using Controller Group

This defines a Controller group for `UserController` Class which all the routes should start with `/user`.
```php
...
use Kim\Service\Router\Router;
use Kim\Service\Router\Route;

Router::controller('/user', UserController::class, [
    Route::get('/login', 'getLogin'),
]);
```
for example the `getLogin()` method of `UserController` is called if there is a get request to the `/user/login` route

- **first param:** the route suffix for the group
- **second param:** the controller class
- **third param:** array of `Route::method()` to define methods' routes

You can see list of `Route::method()` below
