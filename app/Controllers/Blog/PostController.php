<?php

namespace app\Controllers\Blog;

use app\Controllers\Controller;

class PostController extends Controller {

    public function demo () {
        Response()->String('Hello World');
    }

}
