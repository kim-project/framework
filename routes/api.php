<?php

use app\Controllers\UserController;
use Kim\Service\Router\Router;

Router::get('/me', [UserController::class, 'me']);
