# Response

- [Introduction](#introduction)
- [using response function](#using-response-function)
  - [Response types](#response-types)
    - [String output](#string-output)
    - [Json output](#json-output)
    - [View output](#view-output)
    - [File output](#file-output)
    - [File Download](#file-download)
  - [Setting Headers](#setting-headers)
  - [Setting status code](#setting-status-code)
  - [Redirecting to another page](#redirecting-to-another-page)
  - [Sending errors](#sending-errors)
    - [Showing error for api routes](#showing-error-for-api-routes)

## Introduction

There are multiple ways to send a response to the request

-----

## using response function

To send response we can use the global function `response()` inside a controller's method which returns a `Respone` object

### Response Types

#### String output

To output basic `string` (can contain html) you can use this function

```php
return response()->string($data);
```

this will print the `$data` variable

- **first param:** the `string` that you want to print

#### Json output

To output `array` and `Arrayable`s for api usage you can use this function

```php
return response()->json($data);
```

this will print the json encoded `$data` variable

- **first param:** the `array` or `Arrayable` variable that you want to print

#### View output

To output `View` files you can use this function and send required data to the view

```php
return response()->view('home.php', $data);
```

this will render the `app/Views/home.php` View with the data sent with the `$data` variable

- **first param:** the name of the view file in the `app/Views` directory
- **second param:** the `array` of variables that you want to pass

#### File output

To output file you can use this function and show the file in response

```php
return response()->file($file);
```

this will show the file to client

- **first param:** the `File` object or path to file

#### File Download

To output file you can use this function and send the file in response

```php
return response()->fileDownload($file);
```

this will make the client download the file

- **first param:** the `File` object or path to file

-----

### Setting Headers

You can set headers for the response

```php
return response()->header('Content-type', 'application/json')->json($data);
```

This will set the `Content-type` header to `application/json` and then send the response

- **header first param:** the header name
- **header second param:** the header value

You can also set multiple at the same time as an array

```php
return response()->withHeaders([
  'Content-type' => 'application/json',
  //more headers..
  ])->json($data);
```

This will set the headers in the array and then send the response

- **withHeaders first param:** array of headers to set

-----

### Setting status code

You can specify the status code of the response

```php
return response(200)->string($data);
```

This will respond to the request with the `200` status code  

- **response first param:** the status code of the response

**Warning** status codes equal or bigger than `400` will be considered errors

-----

### Redirecting to another page

You can use the `redirect()` method to redirect the client

```php
return response()->redirect('/login');
```

- **first param:** the path to redirect to

-----

### Sending errors

You can use the status codes bigger than `400` to show errors

```php
return response(404, 'Page not found');
```

- **response first param:** the status code of the error
- **response second param:** the message to be printed

#### Showing error for api routes

If you want to show the error in json format you can use

```php
return response(404, 'Page not found', true);
```

- **response third param:** if the response should be json
