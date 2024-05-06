# View Files

Views are files located in the `app/Views/` directory which has our `HTML` code and the front-end of our site
  
They can have any extention eg. `.php`, `.html`, etc  
  
These files are used by controller's in the responses with the variables passed to it which are available in the `$data` variable inside the view, And then rendered and shown to the user
```
├───app
│   ├───Controllers
│   ├───Models
│   └───Views
│       ├───errors.php
│       └───home.php
├───database
├───docs
├───public
├───Routes
├───Service
└───storage
```
- `errors.php` is a View which shows our server's error responses to the user **DO NOT DELETE THIS FILE** but you can customize it
- `home.php` it the default homepage of the site you can delete or edit this file to your liking

## Creating View

You can create a `View` by running the Following Command in the terminal
```shell
php Kim make:view
```
or it's short forms
```shell
php Kim make:v
php Kim m:view
```
Which will give you the following prompt
```bash
$ php Kim make:view

[  Making a View ]


Enter View Name:                   
```
now just enter the name of the view and it will create the view for you
```shell
$ php Kim make:view

[  Making a View ]


Enter View Name:          test


Creating Test in Dir '/app/Views/Test.php'...
Created Successfully.
```
now if you check your `app/Views` Folder
```
├───Views
│   ├───errors.php
│   ├───home.php
│   └───test.php
```
### Creating subdirectories

If you want to create a controller in a subfolder of `app/Views` you can specify the folder name before the name of the controller
```shell
php Kim make:view

[  Making a View ]


Enter View Name:          blog/user


Creating User in Dir '/app/Views/Blog/User.php'...
Created Successfully.
```
and as you can see there is a folder called `Blog` in the `Views` folder with a file called `User.php`
```
├───Views
│   ├───Blog
│   │   └───User.php
│   ├───errors.php
│   └───home.php
```
### Creating Manually
Or you can just create them manually by creating a file in the `app/Views/` directory or subdirectories that contains `HTML` and `PHP` code
## Structure
Now if we go to the file we just created we will see the following code
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
</head>
<body>
    Hello World
</body>
</html>
```
now we have a view which we can pass to response  
```php
response()->View('Blog/User.php', []);
```
which will result in the `User.php` file to be rendered  
  
Now we can put our own `HTML` code inside the file
