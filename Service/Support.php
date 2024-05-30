<?php

use Kim\Service\Auth\JWT;
use Kim\Support\Helpers\Arrayable;
use Kim\Support\Helpers\Collection;
use Kim\Support\Helpers\File;
use Kim\Support\Helpers\Response;

/**
 * Get the error message of the error code
 *
 * @param  int  $error_code  http error code
 *
 * @return string
 */
function get_error_message(int $error_code): string
{
    $codes = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        408 => 'Request Timeout',
        409 => 'Conflict',
        413 => 'Payload Too Large',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
    ];

    return array_key_exists($error_code, $codes) ? $codes[$error_code] : $codes[500];
}

/**
 * Get a response with the corresponding status code
 *
 * @param  int  $status  status code of the response
 * @param  string  $message  error message if the status code is an error
 * @param  bool  $api  if the error should be json
 *
 * @return Response
 */
function response(int $status = 200, string $message = '', ?bool $api = null): Response
{
    $api = $api !== null ? $api : IS_API;
    if ($status < 100) {
        throw new Exception($message, $status);
    } elseif ($status >= 400) {
        if ($api) {
            (new Response($status))->json([
                'status' => $status,
                'message' => $message,
            ])();
        }
        (new Response($status))->view('errors.php', [
            'message' => $message,
            'status' => $status,
        ])();
    }

    return new Response($status);
}

/**
 * Redirect to url
 *
 * @param  string  $url  The url to redirect to
 *
 * @return void
 */
function redirect(string $url): void
{
    header("Location: $url");
    die;
}

/**
 * Create a new File
 *
 * @param  string  $path  The path in which the file should be created
 * @param  string  $content  The content to put in the file
 *
 * @return File
 */
function createFile(string $path, string $content = ''): File
{
    File::checkFileDir(array_filter(explode('/', $path)));
    $file = fopen(__ROOT__.$path, 'x');
    fwrite($file, $content);
    fclose($file);

    return new File($path);
}

/**
 * Get a File
 *
 * @param  string  $path  The path in which the file should be created
 *
 * @return File
 */
function getFile(string $path): ?File
{
    if (file_exists($path)) {
        return new File($path);
    } else {
        return null;
    }
}

function collect(array $array): Collection
{
    return new Collection($array);
}

/**
 * Check csrf token
 *
 * @return bool if the csrf is valid
 */
function csrf(): bool
{
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);

    if (! $token || $token !== $_SESSION['token']) {
        response(405, 'Method Not Allowed');
        exit;
    }

    return true;
}

function JWT(): JWT
{
    return JWT::core();
}
