<?php

namespace Kim\Service\Auth;

use Kim\Support\Helpers\Singleton;
use Kim\Support\Provider\Commands;

class JWT
{
    use Singleton {getInstance as core;}
    private string $secret;

    private function __construct()
    {
        $secret = env('APP_SECRET', '');
        if ($secret !== '') {
            $this->secret = $secret;
        } else {
            $this->secret = Commands::KeyGen();
        }
    }

    public function generate(array $payload): string
    {
        $payload = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.".$this->token_encode($payload);
        $sign = hash_hmac("sha256", $payload, $this->secret, true);

        return $payload.".".$this->token_encode($sign);
    }

    public function verify(string $token): array|bool
    {
        $token = explode(".", $token);

        if (hash_equals(hash_hmac("sha256", $token[0].".".$token[1], $this->secret, true), $this->token_decode($token[2], false))) {
            return $this->token_decode($token[1]);
        } else {
            return false;
        }
    }

    private function token_encode(array|string $payload): string
    {
        if (is_array($payload)) {
            $payload = json_encode($payload);
        }

        $payload = strtr(base64_encode($payload), '+/', '-_');

        return rtrim($payload, '=');
    }

    private function token_decode(string $token, bool $is_payload = true): string|array
    {
        $token = base64_decode(strtr($token, '-_', '+/'));

        return $is_payload ? json_decode($token, true) : $token;
    }
}
