<?php

use app\Controllers\UserController;
use Kim\Service\Router\Route;
use Kim\Service\Router\Router;

Router::controller('/', UserController::class, [
    Route::get('/login', 'getLogin'),
    Route::post('/login', 'login'),
    Route::get('/signup', 'getSignup'),
    Route::post('/signup', 'signup'),
]);
