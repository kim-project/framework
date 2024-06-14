<?php

namespace Kim\Service\Request;

use Kim\Support\Helpers\File;

class UploadedFile extends File
{
    /**
     * @var string The uploaded file name
     */
    private string $name;

    /**
     * @var string The uploaded file type
     */
    private string $type;

    /**
     * @var string The uploaded file full_path
     */
    private string $full_path;

    /**
     * @var int The uploaded file size
     */
    public int $size;

    public function __construct(string $name, string $type, string $tmp_name, int $error, int $size, string $full_path)
    {
        if ($error) {
            throw new \Exception('Bad Request', 400);
        }
        $this->name = $name;
        $this->type = $type;
        $this->path = $tmp_name;
        $this->size = $size;
        $this->full_path = $full_path;
    }

    /**
     * Save file to storage folder in the specified path
     *
     * @param  string  $path  The path in to save the file in
     * @param  bool  $toStorage  If you want to save the file outside the Storage folder set this to false
     *
     * @return bool If the save was successful
     */
    public function save(string $path, bool $toStorage = true): bool
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
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get file's size in bytes
     *
     * @return int file's size
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Get file's uploaded name
     *
     * @return string file's uploaded name
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get file's uploaded type
     *
     * @return string file's uploaded type
     */
    public function type(): string
    {
        return $this->type;
    }
}
