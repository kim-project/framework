<?php

namespace Kim\Support\Helpers;

use Kim\Support\Database\DB;
use Kim\Support\Provider\View;

class Response
{
    /**
     * @var ?int Status code of the response
     */
    private ?int $status;

    /**
     * @var View|File|Arrayable|array|string|null The response's body
     */
    private View|File|Arrayable|array|string|null $res;

    /**
     * @var string[] The response's headers
     */
    private array $headers = [];

    /**
     * @var bool If the file response should force download
     */
    private bool $download = false;

    /**
     * Create Response
     *
     * @param ?int $status Http status code of the response
     * @param mixed $res The response body
     */
    public function __construct(?int $status = null, mixed $res = null)
    {
        $this->status = $status;
        $this->res = $res;
    }

    /**
     * Send the response
     */
    public function __invoke()
    {
        if (isset($this->status)) {
            http_response_code($this->status);
        }

        foreach ($this->headers as $header) {
            header($header);
        }

        $res = $this->res;
        if ($res instanceof Arrayable) {
            $res = $res->toArray();
        }
        if ($res instanceof View) {
            $res->render();
        } elseif ($res instanceof File) {
            if($this->download) {
                $res->download();
            } else {
                $res->response();
            }
        } elseif (is_string($res)) {
            echo $res;
        } elseif (is_array($res) || is_object($res)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($res);
        }
        DB::close();
        exit;
    }

    /**
     * Set response status code
     *
     * @param  int  $status  The status code
     *
     * @return Response
     */
    public function status(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Response a View file
     *
     * @param  string  $view  The view file path in the View Folder
     * @param  array  $data  The data to be passed to the View
     *
     * @return Response
     */
    public function view(string $view, array $data): self
    {
        if (substr($view, 0, 1) === '/') {
            $view = substr($view, 1);
        }
        $view = "app/Views/$view";
        if (!file_exists($view)) {
            throw new \Exception("View $view not found");
        }
        $this->res = new View($view, $data);
        return $this;
    }

    /**
     * Response a String
     *
     * @param  mixed  $res  The content to return
     *
     * @return Response
     */
    public function string(mixed $res): self
    {
        $this->res = strval($res);
        return $this;
    }

    /**
     * Response a Json output
     *
     * @param  array|Arrayable  $res  The content to json encode
     *
     * @return Response
     */
    public function json(array|object $res): self
    {
        $this->res = $res;
        return $this;
    }

    /**
     * Response a File
     *
     * @param  File|string  $file  File or path to file
     *
     * @return Response
     */
    public function file(File|string $file): self
    {
        if($file instanceof File) {
            $this->res = $file;
        } else {
            $this->res = new File($file);
        }
        return $this;
    }

    /**
     * Response a File Download
     *
     * @param  File|string  $file  File or path to file
     *
     * @return Response
     */
    public function fileDownload(File|string $file): self
    {
        if($file instanceof File) {
            $this->res = $file;
        } else {
            $this->res = new File($file);
        }
        $this->download = true;
        return $this;
    }

    /**
     * Redirect to location
     *
     * @param  string  $location  The url to redirect to
     *
     * @return Response
     */
    public function redirect(string $location): self
    {
        $this->headers[] = "Location: $location";
        return $this;
    }

    /**
     * Set a new header for response
     *
     * @param  string  $header  The header key
     * @param  string  $value  The header value
     *
     * @return Response
     */
    public function header(string $header, string $value): self
    {
        $this->headers[] = "$header: $value";
        return $this;
    }

    /**
     * Set new headers for response
     *
     * @param  array  $headers  array of headers as key => value pair
     *
     * @return Response
     */
    public function withHeaders(array $headers): self
    {
        foreach ($headers as $key => $value) {
            $this->headers[] = "$key: $value";
        }
        return $this;
    }
}
