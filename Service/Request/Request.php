<?php

namespace Kim\Service\Request;

use Kim\Service\Router\Server;
use Kim\Support\Helpers\Arrayable;

class Request extends Arrayable
{
    private static array $php_input;

    private array $files = [];

    private array $query = [];

    private array $request = [];

    private array $headers = [];

    private array $session = [];

    private array $cookie = [];

    public function __construct()
    {
        $this->query = $_GET;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->request = $_POST;
            $this->files = array_map(function ($item) {
                return new UploadedFile(...$item);
            }, $_FILES);

        } elseif ($_SERVER['REQUEST_METHOD'] != 'GET') {

            $this->request = self::parseInput();

        }

        $this->cookie = $_COOKIE;
        $this->session = $_SESSION;
    }

    private function getRequestHeaders()
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {

            if (substr($key, 0, 5) != 'HTTP_') {
                continue;
            }
            $header = ucwords(str_replace('_', '-', strtolower(substr($key, 5))));
            $headers[$header] = $value;

        }

        $this->headers = $headers;
    }

    public static function parseInput(): array
    {
        if(!isset(self::$php_input)) {
            $result = [];
            $raw = file_get_contents('php://input');

            switch (explode(';', $_SERVER['CONTENT_TYPE'])[0]) {
                case 'application/json':
                    $result = json_decode($raw, true);
                    break;

                case 'application/x-www-form-urlencoded':
                    parse_str($raw, $result);
                    break;
            }

            self::$php_input = $result;
        }
        return self::$php_input;
    }

    public function file(string|array $field): ?UploadedFile
    {
        return self::getOnly($field, $this->files);
    }

    public function files(): array
    {
        return $this->files;
    }

    public function query(string|array $field): mixed
    {
        return self::getOnly($field, $this->query);
    }

    public function queries(): array
    {
        return $this->query;
    }

    public function input(string|array $field): mixed
    {
        return $this->only($field);
    }

    public function all(): array
    {
        return $this->toArray();
    }

    public function header(string $header): mixed
    {
        return self::getOnly($header, $this->headers);
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function validate(array $rules): Validator
    {
        return new Validator($this, $rules);
    }

    public function toArray(): array
    {
        return $this->request;
    }

    public function path(): string
    {
        return Server::getRoute();
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}
