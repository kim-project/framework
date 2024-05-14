# Files

You can use Kim's built-in File handler

To start using it you can use the following line to open a file

```php
$file = getFile('/storage/app.json');
```

or using the class

```php
use Kim\Support\Helpers\File;

$file = new File('/storage/app.json');
```

To create a new file

```php
$file = createFile('/storage/app.txt');
```

or if you want to put content in it

```php
$file = createFile('/storage/app.txt', 'Hello world');
```

all of them will return a `\Kim\Support\Helpers\File::class` on success which has the following methods

-----

## File Methods

### Read

read the file's content

```php
$content = $file->read();
```

### Write

write to file

```php
$file->write($content);
```

### Json

read the file's json content and return as array

```php
$array = $file->json();
```

### Path

return file's path

```php
$path = $file->path();
```

### Mime Type

return file's mime content type

```php
$type = $file->mimeType();
```

### Size

return file's size in bytes

```php
$size = $file->size();
```

### Name

return file's name

```php
$name = $file->name();
```

### LastModified

return file's last modification date

```php
$array = $file->lastModified();
```

-----

## Response

response the file to the client

```php
public function image() {

    $file = getFile('/storage/image.png');
    $file->response();

}
```

-----

## Download

make the client download the file

```php
public function image() {

    $file = getFile('/storage/image.png');
    $file->download();

}
```
