# Route Files

- [Introduction](#introduction)
- [What do they do](#what-do-they-do)

## Introduction

There is a `Routes/` directory in the project directory with two files which handle our routes in the server

```
├───app
├───database
├───docs
├───public
├───Routes
│   ├───api.php
│   └───web.php
├───Service
└───storage
```

- `api.php` is for api routes which start with `/api/` suffix
- `web.php` is for other general routes

-----

## What do they do

Route Files have the following Structure

```php
<?php

use app\Controllers\UserController;
use Kim\Service\Router\Route;
use Kim\Service\Router\Router;

Router::get('/', function () {
    response()->View('home.php', []);
});
Router::controller('/', UserController::class, [
    Route::get('/login', 'getLogin'),
    Route::post('/login', 'login'),
    Route::get('/signup', 'getSignup'),
    Route::post('/signup', 'signup'),
]);
```

They are a bunch of rules with patterns that if the request `url` matches any of this patterns they will handle the request by the `function` or `method` passed to them and won't continue the code and check the other rules
