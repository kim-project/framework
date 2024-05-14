# Route Patterns

You can set dynamic patterns for the routes and recieve them as function params (Also as Controller methods)

Using `:` followed by a name like `:id` will pass you a variable with the same name `$id`

```php
Router::get('/post/:postid', function ($postid) {
    //Use the $postid recieved from the route
    ...
});
```

For example if there's a request to `/post/123` the `123` will be passed to function in the `$postid` variable

Another example for `Controllers` can be found in the [Requests](Controllers/Requests.md) page
