<?php

namespace Kim\Auth;

use Kim\Support\Singleton;
use Kim\Console\Commands;

class JWT
{
    use Singleton {getInstance as core;}

    /**
     * @var string The JWT hash secret
     */
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

    /**
     * Generate JWT token with custom payload
     *
     * @param array $payload The payload of the token
     * @param ?int $expire The expire time of the token
     *
     * @return string The JWT token
     */
    public function generate(array $payload, ?int $expire = null): string
    {
        if($expire !== null) {
            $payload['exp'] = $expire;
        }
        $payload = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.".$this->base64UrlEncode($payload);
        $sign = hash_hmac("sha256", $payload, $this->secret, true);

        return $payload.".".$this->base64UrlEncode($sign);
    }

    /**
     * Verify the JWT token
     *
     * @param string $token The JWT token
     *
     * @return array|false returns the payload if valid and false for invalid
     */
    public function verify(string $token): array|bool
    {
        $token = explode(".", $token);

        if (hash_equals(hash_hmac("sha256", $token[0].".".$token[1], $this->secret, true), $this->base64UrlDecode($token[2], false))) {
            $token = $this->base64UrlDecode($token[1]);
            if (isset($token['exp']) && time() > $token['exp']) {
                return false;
            }
            return $token;
        } else {
            return false;
        }
    }

    /**
     * Base64 Url Encode
     *
     * @param array|string $payload The data to encode
     *
     * @return string returns the base64 url encoded string
     */
    private function base64UrlEncode(array|string $payload): string
    {
        if (is_array($payload)) {
            $payload = json_encode($payload);
        }

        $payload = strtr(base64_encode($payload), '+/', '-_');

        return rtrim($payload, '=');
    }

    /**
     * Base64 Url Decode
     *
     * @param string $token The encoded data
     * @param bool $is_payload If the encoded data is payload (array)
     *
     * @return string|array returns the base64 url encoded data
     */
    private function base64UrlDecode(string $token, bool $is_payload = true): string|array
    {
        $token = base64_decode(strtr($token, '-_', '+/'));

        return $is_payload ? json_decode($token, true) : $token;
    }
}
