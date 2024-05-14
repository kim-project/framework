# Controller Files

Controllers are classes located in the `app/Controllers/` directory which extend the `\app\Controllers\Controller` class in the `Controller.php` File  
  
These files should have defined methods which will be called by `Router`s if the route matches the right pattern

```
├───app
│   ├───Controllers
│   │   ├───Controller.php
│   │   └───UserController.php
│   ├───Models
│   └───Views
├───database
├───docs
├───public
├───Routes
├───Service
└───storage
```

- `UserController.php` is a Controller class
- `Controller.php` it is the class that controllers extend **DO NOT DELETE THIS FILE**

-----

## Creating Controller

You can create a `Controller` by running the Following Command in the terminal

```shell
php Kim make:controller
```

or it's short forms

```shell
php Kim make:c
php Kim m:controller
```

Which will give you the following prompt

```bash
$ php Kim make:controller

[  Making a controller ]


Enter Controller Name:           
```

now just enter the name of the controller and it will create the controller for you

```shell
$ php Kim make:controller

[  Making a controller ]


Enter Controller Name:          post


Creating app\Controllers\PostController in Dir '/app/Controllers/PostController.php'...
Created Successfully.
```

now if you check your `app/Controllers` Folder

```
├───Controllers
│   ├───Controller.php
│   ├───PostController.php
│   └───UserController.php
```

### Creating subdirectories

If you want to create a controller in a subfolder of `app/Controllers` you can specify the folder name before the name of the controller

```shell
php Kim make:controller

[  Making a controller ]


Enter Controller Name:          blog/post


Creating app\Controllers\Blog\PostController in Dir '/app/Controllers/Blog/PostController.php'...
Created Successfully.
```

and as you can see there is a folder called `Blog` in the `Controllers` folder with a file called `PostController.php`

```
├───Controllers
│   ├───Blog
│   │   └───PostController.php
│   ├───Controller.php
│   └───UserController.php
```

-----

## Structure

Now if we go to the file we just created we will see the following code

```php
<?php

namespace app\Controllers\Blog;

use app\Controllers\Controller;

class PostController extends Controller {

    public function demo () {
        Response()->String('Hello World');
    }

}
```

now we have a controller with a `demo()` method which we can pass to routers to see the `Hello World` response  

```php
Router::get('/demo', [PostController::class, 'demo']);
```

or with a group

```php
Router::controller('/', PostController::class, [
    Route::get('/demo', 'demo'),
]);
```

Both these codes will make any `GET` call to the route `/demo` to get the `Hello World` response  
  
finally we can start creating our own methods to pass to routers
