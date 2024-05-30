<?php

namespace Kim\Service\Request;

use Kim\Service\Router\Server;
use Kim\Support\Helpers\Arrayable;
use Kim\Support\Helpers\Singleton;

class Request
{
    use Arrayable, Singleton {Singleton::getInstance as getRequest;}

    private static array $php_input;

    public string $method;

    public string $route;

    private array $files = [];

    private array $query = [];

    private array $request = [];

    private array $headers = [];

    private array $session = [];

    private array $cookie = [];

    private function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = $_GET;

        //Parse Url
        $this->route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($this->method === 'POST') {

            $this->request = $_POST;
            $this->files = array_map(function ($item) {
                return new UploadedFile(...$item);
            }, $_FILES);

        } elseif ($this->method !== 'GET') {

            $this->request = $this->parseInput();

        }

        $this->cookie = $_COOKIE;
        $this->session = $_SESSION;
    }

    private function getRequestHeaders(): void
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {

            if (substr($key, 0, 5) !== 'HTTP_') {
                continue;
            }
            $header = ucwords(str_replace('_', '-', strtolower(substr($key, 5))));
            $headers[$header] = $value;

        }

        $this->headers = $headers;
    }

    private function parseInput(): array
    {
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

        return $result;
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

    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}
