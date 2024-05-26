<?php

namespace Kim\Support\Provider;

use Kim\Support\Helpers\File;

class Commands
{
    private const Kim = "\x1b[36m\n_________ ______________  ___\n _____  //_/___  _/__   |/  /\n  ___  ,<   __  / __  /|_/ /\n   _  /| | __/ /  _  /  / /\n   /_/ |_| /___/  /_/  /_/\n\n\n\x1b[37m";

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
                $parse['namespace'] === 'app\Controllers' ? '' : "\n\nuse app\Controllers\Controller;",
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
        echo self::Kim."Server Running at [http://localhost:8000]\n\n";
        shell_exec('php -S localhost:8000 app.php');
    }

    public static function Update(): void
    {
        $raw = 'https://raw.githubusercontent.com/kim-project/framework/master';
        ini_set('allow_url_fopen', 1);
        $rKim = file_get_contents($raw.'/Service/Kim.json');
        $Kim = json_decode($rKim, true);
        $curr = new File('/Service/Kim.json');
        if ($Kim['version'] !== $curr->json()['version']) {
            echo "\n\x1b[33m[\x1b[36m  Updating Kim \x1b[33m]\n\n\n";
            $i = 1;
            $count = count($Kim['files']);
            foreach ($Kim['files'] as $file) {
                echo "\x1b[33m[\x1b[0m$i\x1b[33m\\\x1b[0m$count\x1b[33m] \x1b[0mChecking file '\x1b[33m$file\x1b[0m'...\n";
                $cont = file_get_contents($raw.$file);
                if (file_exists(__ROOT__.$file)) {
                    $f = new File($file);
                    if ($f->read() !== $cont) {
                        echo "\x1b[33m[\x1b[0m$i\x1b[33m\\\x1b[0m$count\x1b[33m] \x1b[36mUpdating file '\x1b[33m$file\x1b[36m'...";
                        $f->write($cont);
                        echo " done\n";
                    }
                } else {
                    echo "\x1b[33m[\x1b[0m$i\x1b[33m\\\x1b[0m$count\x1b[33m] \x1b[36mCreating file '\x1b[33m$file\x1b[36m'...";
                    createFile($file, $cont);
                    echo " done\n";
                }
                $i++;
            }
            echo "\n\n\x1b[37mUpating Kim Version!";
            $curr->write($rKim);
            echo "\n\n\x1b[37mKim Updated Successfully!\n\n\n\x1b[0m";
        } else {
            echo "\n\n\n\x1b[33m[\x1b[36m  Kim is up to date! \x1b[33m]\n\n\n\x1b[0m";
        }
    }

    public static function KeyGen(): string
    {
        $ini = parse_ini_file(__ROOT__.'/.env');
        $secret = rtrim(base64_encode(random_bytes(64)), '=');
        unset($ini['APP_SECRET']);

        $ini = array_map(function (string $k, string $v) {
            return "$k=$v";
        }, array_keys($ini), array_values($ini));

        file_put_contents(__ROOT__.'/.env', 'APP_SECRET='.$secret.PHP_EOL.implode(PHP_EOL, $ini)).PHP_EOL;

        return $secret;
    }

    public static function Kim(): void
    {
        echo self::Kim;
    }
}
