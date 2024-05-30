<?php

use app\Controllers\UserController;
use Kim\Service\Request\Request;
use Kim\Service\Router\Route;
use Kim\Service\Router\Router;

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
Router::get('/header', function (Request $request) {
    return $_COOKIE;
});
