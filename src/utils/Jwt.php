<?php

namespace Utils;

class Jwt
{
    private static string $secret = 'CHANGE_ME';

    public static function generate(array $payload): string
    {
        $header = base64_encode(json_encode(['alg'=>'HS256','typ'=>'JWT']));
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", self::$secret, true);
        return "$header.$payload." . base64_encode($signature);
    }
}
