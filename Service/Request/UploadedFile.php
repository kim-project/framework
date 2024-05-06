<?php

namespace Kim\Service\Request;

use Kim\Support\Helpers\File;

class UploadedFile extends File
{
    /**
     * The uploaded file name
     *
     * @var string
     */
    private string $name;

    /**
     * The uploaded file type
     *
     * @var string
     */
    private string $type;

    /**
     * The uploaded file size
     *
     * @var int
     */
    public int $size;

    public function __construct(string $name, string $type, string $tmp_name, int $error, int $size)
    {
        if ($error) {
            throw new \Exception('Bad Request', 400);
        }
        $this->name = $name;
        $this->type = $type;
        $this->path = $tmp_name;
        $this->size = $size;
    }

    /**
     * Save file to storage folder in the specified path
     *
     * @param  string  $path  The path in to save the file in
     * @param  bool  $toStorage  If you want to save the file outside the Storage folder set this to false
     *
     * @return string|false
     */
    public function save(string $path, bool $toStorage = true): string|bool
    {
        $path = array_filter(
            explode('/', $path)
        );
        if ($toStorage) {
            array_unshift($path, 'Storage');
        }
        self::checkFileDir($path);
        $path = implode('/', $path);
        if (move_uploaded_file($this->path, $path)) {
            return $path;
        } else {
            return false;
        }
    }

    /**
     * Get file's size in bytes
     *
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Get file's uploaded name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get file's uploaded type
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }
}
