# Kim's Request Handler

- [Introduction](#introduction)
- [Using request handler](#using-request-handler)
- [Methods](#methods)
  - [Inputs](#inputs)
  - [Query Parameters](#query-parameters)
  - [Files](#files)

## Introduction

You can use the framework's request handler for easier access to the inputs

-----

## Using request handler

To use the request handler you should set a parameter named `$request` for your function or controller's method then you can use this variable to handle the inputs

```php
...

use Kim\Service\Request\Request;

Router::get('/', function (Request $request) {
    //You can use the request handler here
});
```

```php
<?php

namespace app\Controllers;

use app\Controllers\Controller;
use Kim\Service\Request\Request;

class PostController extends Controller {

    public function demo (Request $request) {
        // You can use the request handler here
    }

}
```
  
Now let's get started with using the request handler

-----

## Methods

### Inputs

You can get the request's body and fields using the handler (this will show body from the `POST`,`PUT`, `DELETE` methods)

#### Getting specific field

```php
public function demo (Request $request) {
    $username = $request->input('username');

    // You can use the input here
}
```
  
This will get the field `username` from the body
  
#### Getting multiple fields

You can also get multiple fields at once

```php
public function demo (Request $request) {
    $credentials = $request->input(['username', 'password']);

    $credentials['username'];
    $credentials['password'];
    // You can use the input here
}
```

This will result in `$credentials` being an associative array containing `username` and `password`

#### Getting all fields

You can also get the whole input fields

```php
public function demo (Request $request) {
    $input = $request->all();

    $input['username'];
    $input['password'];
    // You can use the input here
}
```

-----

### Query Parameters

You can get the request's url parameters (`$_GET`'s content) like the following

#### Getting query parameters

```php
public function demo (Request $request) {
    $page = $request->query('page');

    // You can use the parameter here
}
```
  
This will get the field `page` from the url parameters (eg. `localhost/posts?page=20`)

And like the `input` method you can pass multiple fields in an array

```php
public function demo (Request $request) {
    $query = $request->query(['page', 'limit']);

    $query['page'];
    $query['limit'];
    // You can use the parameter here
}
```

This will result in `$query` being an associative array containing `page` and `limit` (eg. `localhost/posts?page=20&limit=10`)

#### Getting all parameters

You can also get the whole query parameters

```php
public function demo (Request $request) {
    $query = $request->queries();

    $query['page'];
    $query['limit'];
    // You can use the parameter here
}
```

-----

### Files

You can get the request's files sent by the user (This will return the uploaded files as the `\Kim\Service\Request\UploadedFile` class)

#### Getting specific files

```php
public function demo (Request $request) {
    $media = $request->file('media');

    // You can use the parameter here
}
```
  
This will get the field `media` from the files that are uploaded

And like the `input` method you can pass multiple fields in an array

```php
public function demo (Request $request) {
    $files = $request->file(['media', 'thumbnail']);

    $files['media'];
    $files['thumbnail'];
    // You can use the parameter here
}
```

This will result in `$files` being an associative array containing `media` and `thumbnail` files

#### Getting all files

You can also get the whole query parameters

```php
public function demo (Request $request) {
    $files = $request->files();

    $files['page'];
    $files['limit'];
    // You can use the parameter here
}
```

-----

...
