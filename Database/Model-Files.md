# Model Files

- [Introduction](#introduction)
- [Creating Model](#creating-model)
  - [Creating subdirectories](#creating-subdirectories)
- [Structure](#structure)
- [Usage](#usage)

## Introduction

Models are classes located in the `app/Models/` directory
  
```
├───app
│   ├───Controllers
│   ├───Models
│   │   └───User.php
│   └───Views
├───database
├───docs
├───public
├───Routes
├───Service
└───storage
```

- `User.php` is a Model for handling Users

-----

## Creating Model

You can create a `Model` by running the Following Command in the terminal

```shell
php Kim make:model
```

or it's short forms

```shell
php Kim make:m
php Kim m:model
```

Which will give you the following prompt

```bash
$ php Kim make:model

[  Making a Model ]


Enter Model Name:           
```

now just enter the name of the model and it will create the model for you

```shell
$ php Kim make:model

[  Making a Model ]


Enter Model Name:          post


Creating app\Models\Post in Dir '/app/Models/Post.php'...
Created Successfully.
```

now if you check your `app/Models` Folder

```
├───Models
│   ├───Post.php
│   └───User.php
```

### Creating subdirectories

If you want to create a model in a subfolder of `app/Models` you can specify the folder name before the name of the model

```shell
php Kim make:model

[  Making a Model ]


Enter Model Name:          blog/post


Creating app\Models\Blog\Post in Dir '/app/Models/Blog/Post.php'...
Created Successfully.
```

and as you can see there is a folder called `Blog` in the `Models` folder with a file called `Post.php`

```
├───Models
│   ├───Blog
│   │   └───Post.php
│   └───User.php
```

-----

## Structure

Now if we go to the file we just created we will see the following code

```php
<?php

namespace app\Models;

use Kim\Support\Database\DB;

class Post {

    public static function select(string $where) : array
    {
        return DB::fetch("SELECT * FROM `post` $where");
    }

    public static function find(string|int $id) : array|null
    {
        return DB::first("SELECT * FROM `post` WHERE `id`='$id'");
    }

    public static function first(string $where): array|null
    {
        return DB::first("SELECT * FROM `post` $where");
    }

    public static function delete(string|int $id) : bool
    {
        return DB::sql("DELETE FROM `post` WHERE `id`='$id'");
    }

}

```

You can modify the queries however you want or add custom `static` functions

## Usage

now we can use the function we created to execute sql queries  

```php
use app\Models\Post;

$posts = Post::select("WHERE `category`='update'"); //List of all posts with the 'update' category

$latestpost = Post::first("ORDER BY `Date` DESC"); //Getting the latest post    
$latestpost['title']; //title of the latest post

$post = Post::find('12301') //Find post by id

Post::delete('12301') //Delete a post by id
```

example of the default functions
