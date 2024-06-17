<?php

use app\Controllers\UserController;
use Kim\Router\Route;
use Kim\Router\Router;

Router::get('/', function () {
    return response()->view('home.php', []);
});
Router::controller('/', UserController::class, [
    Route::get('/login', 'getLogin'),
    Route::post('/login', 'login'),
    Route::get('/logout', 'logout'),
    Route::get('/signup', 'getSignup'),
    Route::post('/signup', 'signup'),
]);
