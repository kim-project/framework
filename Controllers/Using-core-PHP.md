# Using Core PHP

As you probably already know php has superglobals for handling request inputs (`$_GET`, `$_POST`, `$_FILES`, `$_COOKIE`, `$_SESSION`, `$_REQUEST`)  

But there aren't any superglobals for `PUT` and `DELETE` methods  

So we added our own globals (they aren't superglobals you have to use the `global` scope to access them) to be able to read these requests
  
**Attention:** These two methods only support `application/json` and `application/x-www-form-urlencoded` input types

## PUT

You make the global variable accessible by using `global $_PUT;` to make the variable available in the method

```php
...
public function demo () {
    global $_PUT;

    //Now you can use the $_PUT array to access the inputs

    return $_PUT['name']; //print the 'name' field of the input 
}
...
```

## DELETE

You make the global variable accessible by using `global $_DELETE;` to make the variable available in the method

```php
...
public function demo () {
    global $_DELETE;

    //Now you can use the $_PUT array to access the inputs

    return $_DELETE['name']; //print the 'name' field of the input 
}
...
```
