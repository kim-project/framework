<?php

use app\Controllers\UserController;
use Kim\Service\Request\Request;
use Kim\Service\Router\Router;

Router::get('/me', [UserController::class, 'me']);
Router::post('/', function (Request $request) {
    return $request->all()['music'];
});
