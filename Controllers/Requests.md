# Requests

The main purpose of controllers are handling requests by their methods (or using anonymous functions instead of controller for some routes)

-----

### Passing Route Parameters

**`app/Controllers/PostController.php`**

```php
<?php

namespace app\Controllers;

use app\Controllers\Controller;

class PostController extends Controller {

    public function getComment ($postid, $commentid) {
        // code
    }

}
```

this will get the `$postid` and `$commentid` as parameters from router  
  
Also we have to define patterns for the router to get the parameters  
  
**`Routes/web.php`**

```php
...
use app\Controllers\PostController;

Router::controller('/post/:postid', PostController::class, [
    Route::get('/comment/:commentid', 'getComment'),
]);
...
```
