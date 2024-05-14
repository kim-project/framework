# Response

- [Introduction](#introduction)
- [using response function](#using-response-function)
  - [Response types](#response-types)
    - [String output](#string-output)
    - [Json output](#json-output)
    - [View output](#view-output)
  - [Setting status code](#setting-status-code)
  - [Sending errors](#sending-errors)
    - [Showing error for api routes](#showing-error-for-api-routes)

## Introduction

There are multiple ways to send a response to the request

-----

## using response function

To send response we can use the global function `response()` which returns a `Respone` object

### Response Types

#### String output

To output basic `string` (can contain html) you can use this function

```php
response()->String($data);
```

this will print the `$data` variable

- **first param:** the `string` that you want to print

#### Json output

To output `array` and `Arrayable`s for api usage you can use this function

```php
response()->Json($data);
```

this will print the json encoded `$data` variable

- **first param:** the `array` or `Arrayable` variable that you want to print

#### View output

To output `View` files you can use this function and send required data to the view

```php
response()->View('home.php', $data);
```

this will render the `app/Views/home.php` View with the data sent with the `$data` variable

- **first param:** the name of the view file in the `app/Views` directory
- **second param:** the `array` of variables that you want to pass

-----

### Setting status code

You can specify the status code of the response

```php
response(200)->String($data);
```

This will respond to the request with the `200` status code  

- **response first param:** the status code of the response

**Warning** status codes equal or bigger than `400` will be considered errors

-----

### Sending errors

You can use the status codes bigger than `400` to show errors

```php
response(404, 'Page not found');
```

- **response first param:** the status code of the error
- **response second param:** the message to be printed

### Showing error for api routes

If you want to show the error in json format you can use

```php
response(404, 'Page not found', true);
```

- **response third param:** if the response should be json
