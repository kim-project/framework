<?php

use app\Controllers\UserController;
use Kim\Router\Router;

Router::get('/me', [UserController::class, 'me']);
