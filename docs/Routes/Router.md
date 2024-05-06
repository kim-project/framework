# Router

If you inspect these files they use the `Router` Class for handling routes
```php
...

use Kim\Service\Router\Router;

Router::get('/', function () {
    ...
});
```
For example this code runs the given function if there is a `GET` request to the `/` route
