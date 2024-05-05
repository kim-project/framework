<?php

namespace Kim\Support\Helpers;

use Kim\Support\Database\DB;

class Response
{
    public function __construct(int $status)
    {
        http_response_code($status);
    }

    private function exit(): void
    {
        DB::close();
        exit;
    }

    /**
     * Response a View file
     *
     * @param  string  $view  The view file path in the View Folder
     * @param  array  $data  The data to be passed to the View
     */
    public function View(string $view, array $data): void
    {
        if (substr($view, 0, 1) == '/') {
            $view = substr($view, 1);
        }
        $view = "/app/Views/$view";
        if (file_exists($view)) {
            throw new \Exception("View $view not found", 503);
        }
        $view = file_get_contents('.'.$view);
        eval("?> $view <?php ");
        $this->exit();
    }

    /**
     * Response a String
     *
     * @param  mixed  $res  The content to return
     */
    public function String(mixed $res): void
    {
        echo $res;
        $this->exit();
    }

    /**
     * Response a Json output
     *
     * @param  array|Arrayable  $res  The content to json encode
     */
    public function Json(array|Arrayable $res): void
    {
        if (is_array($res)) {
            echo json_encode($res);
        } else {
            echo $res->toJson();
        }
        $this->exit();
    }
}
