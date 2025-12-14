<?php

namespace HMadou\VsmApi\Middlewares;

use HMadou\VsmApi\Utils\JsonResponse;
use HMadou\VsmApi\Utils\Jwt;

class JwtMiddleware
{
    public static function authenticate(): ?array
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            JsonResponse::unauthorized("Token manquant.");
            return null;
        }

        // On retire juste "Bearer "
        $token = str_replace("Bearer ", "", $headers['Authorization']);

        $payload = Jwt::verify($token);

        if (!$payload) {
            JsonResponse::unauthorized("Token invalide ou expiré.");
            return null;
        }

        return $payload;
    }
}
