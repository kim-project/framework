<?php

namespace Kim\Support\Helpers;

class File
{
    /**
     * The path to file
     */
    protected string $path;

    /**
     * Get File from path
     *
     * @param  string  $path  The path of the file
     */
    public function __construct(string $path)
    {
        if (! file_exists($path)) {
            throw new \Exception("File $path Not Found", 503);
        }
        $this->path = $path;
    }

    /**
     * Get file's path
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Get file's last modification time
     */
    public function lastModified(): int|bool
    {
        return filemtime($this->path);
    }

    /**
     * Get file's mime type
     */
    public function mimeType(): string|bool
    {
        return mime_content_type($this->path);
    }

    /**
     * Get file's size in bytes
     */
    public function size(): int|bool
    {
        return filesize($this->path);
    }

    /**
     * Get file's name
     */
    public function name(): string
    {
        return basename($this->path);
    }

    /**
     * write to file
     *
     * @param  mixed  $content  Content to write to file
     * @return string
     */
    public function write($content): int|bool
    {
        return file_put_contents($this->path, $content);
    }

    /**
     * Read file content
     */
    public function read(): string|bool
    {
        return file_get_contents($this->path);
    }

    /**
     * Read json file's content
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
    public function download()
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"{$this->name()}\"");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: '.$this->size());
        readfile($this->path);
        exit;
    }

    /**
     * Response the file
     */
    public function response(): void
    {
        readfile($this->path);
        exit;
    }

    /**
     * Generate path directories
     */
    public static function checkFileDir(array $path): void
    {
        $path = array_slice($path, 0, -1);
        $i = count($path);
        $subPath = implode('/', array_slice($path, 0, $i));
        while (! is_dir($subPath)) {
            $i--;
            $subPath = implode('/', array_slice($path, 0, $i));
        }
        for ($j = $i + 1; $j < count($path); $j++) {
            $subPath .= $path[$i];
            mkdir($subPath);
        }
    }
}
