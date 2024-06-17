<?php

namespace Kim\Support;

class File
{
    /**
     * @var string The path to file
     */
    protected string $path;

    /**
     * Get File from path
     *
     * @param  string  $path  The path of the file
     */
    public function __construct(string $path)
    {
        if (! file_exists(__ROOT__.$path)) {
            throw new \Exception("File $path Not Found", 503);
        }
        $this->path = __ROOT__.$path;
    }

    /**
     * Get file's path
     *
     * @return string The path to file
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Get file's last modification time
     *
     * @return int|bool file's last modification time
     */
    public function lastModified(): int|bool
    {
        return filemtime($this->path);
    }

    /**
     * Get file's mime type
     *
     * @return string|false file's mime type
     */
    public function mimeType(): string|bool
    {
        return mime_content_type($this->path);
    }

    /**
     * Get file's size in bytes
     *
     * @return int|false file's size
     */
    public function size(): int|bool
    {
        return filesize($this->path);
    }

    /**
     * Get file's name
     *
     * @return string file's name
     */
    public function name(): string
    {
        return basename($this->path);
    }

    /**
     * write to file
     *
     * @param  mixed  $content  Content to write to file
     *
     * @return int|false number of bytes written
     */
    public function write($content): int|bool
    {
        return file_put_contents($this->path, $content);
    }

    /**
     * Read file content
     *
     * @return string|false file's content
     */
    public function read(): string|bool
    {
        return file_get_contents($this->path);
    }

    /**
     * Read json file's content
     *
     * @return array Json decoded file content
     */
    public function json(): array
    {
        return json_decode(file_get_contents($this->path), true);
    }

    /**
     * Make the user download file
     *
     * @return void
     */
    public function download(): void
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"{$this->name()}\"");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: '.$this->size());
        readfile($this->path);
    }

    /**
     * Response the file
     *
     * @return void
     */
    public function response(): void
    {
        header('Content-Type: '.$this->mimeType());
        header('Content-Length:'.$this->size());
        readfile($this->path);
    }

    /**
     * Generate path directories
     *
     * @return void
     */
    public static function checkFileDir(array $path): void
    {
        $path = array_slice($path, 0, -1);
        $i = count($path);
        $subPath = implode('/', array_slice($path, 0, $i)).'/';
        while (! file_exists($subPath)) {
            $i--;
            $subPath = implode('/', array_slice($path, 0, $i)).'/';
        }
        for ($i; $i < count($path); $i++) {
            $subPath .= $path[$i].'/';
            mkdir($subPath);
        }
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->name(),
            'type' => $this->mimeType(),
            'size' => $this->size()
        ];
    }
}
