<?php

namespace Kim\Support\Provider;

use Kim\Support\Helpers\File;

class Commands
{
    private static function parseName(string $name, string $type): array
    {
        $path = array_filter(
            explode(
                '/',
                str_replace('\\', '/', $name)
            )
        );
        $path = array_map(function ($item) {
            return ucfirst($item);
        }, $path);
        array_unshift($path, 'app', $type);
        File::checkFileDir($path);

        return [
            'namespace' => implode('\\', array_slice($path, 0, -1)),
            'path' => implode('/', $path).'.php',
            'name' => end($path),
        ];
    }

    private static function createFile(string $path, array $search, array $replace, string $template): void
    {
        $myfile = fopen($path, 'x') or exit('Unable to open file!');
        fwrite($myfile, str_replace(
            $search,
            $replace,
            file_get_contents(__DIR__.'/../Template/'.$template)
        ));
        fclose($myfile);
    }

    public static function CreateController(): void
    {
        echo "\n\x1b[33m[\x1b[36m  Making a controller \x1b[33m]\n\n\n\x1b[38;5;225m";
        $parse = self::parseName(readline("\x1b[35mEnter Controller Name\x1b[33m:").'Controller', 'Controllers');
        echo "\n\n\x1b[32mCreating {$parse['namespace']}\\{$parse['name']} in Dir '/{$parse['path']}'...\n\x1b[33m";
        self::createFile(
            $parse['path'],
            [
                'namespace:',
                'use:',
                'name:',
            ],
            [
                $parse['namespace'],
                $parse['namespace'] == 'app\Controllers' ? '' : "\n\nuse app\Controllers\Controller;",
                $parse['name'],
            ],
            'Controller.kim'
        );
        echo "\x1b[37mCreated Successfully.\n\x1b[0m";
    }

    public static function CreateModel(): void
    {
        echo "\n\x1b[33m[\x1b[36m  Making a model \x1b[33m]\n\n\n\x1b[38;5;225m";
        $parse = self::parseName(readline("\x1b[35mEnter Model Name\x1b[33m:"), 'Models');
        echo "\n\n\x1b[32mCreating {$parse['namespace']}\\{$parse['name']} in Dir '/{$parse['path']}'...\n\x1b[33m";
        self::createFile(
            $parse['path'],
            [
                'namespace:',
                'name:',
                'table:',
            ],
            [
                $parse['namespace'],
                $parse['name'],
                strtolower($parse['name']),
            ],
            'Model.kim'
        );
        echo "\x1b[37mCreated Successfully.\n\x1b[0m";
    }

    public static function CreateView(): void
    {
        echo "\n\x1b[33m[\x1b[36m  Making a View \x1b[33m]\n\n\n\x1b[38;5;225m";
        $parse = self::parseName(readline("\x1b[35mEnter View Name\x1b[33m:"), 'Views');
        echo "\n\n\x1b[32mCreating {$parse['name']} in Dir '/{$parse['path']}'...\n\x1b[33m";
        self::createFile(
            $parse['path'],
            [
                'name:',
            ],
            [
                $parse['name'],
            ],
            'View.kim'
        );
        echo "\x1b[37mCreated Successfully.\n\x1b[0m";
    }

    public static function Start(): void
    {
        echo "\n\nStarting Server...\n\n";
        shell_exec('php -S localhost:8000 app.php');
    }

    public static function Update() : void {
        $raw = 'https://raw.githubusercontent.com/kim-project/framework/master';
        ini_set("allow_url_fopen", 1);
        $rKim = file_get_contents($raw.'/Service/Kim.json');
        $Kim = json_decode($rKim);
        $curr = new File('/Service/Kim.json');
        if ($Kim['version'] !== $Kim['version']) {
            foreach ($Kim['files'] as $file) {
                $cont = file_get_contents($raw.$file);
                if (file_exists(__ROOT__.$file)) {
                    $f = new File($file);
                    if ($f->read() != $cont) {
                        $f->write($cont);
                    }
                } else {
                    createFile($file, $cont);
                }
            }
        }
        $curr->write(json_encode($rKim));
    }
}
