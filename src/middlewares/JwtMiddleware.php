<?php

namespace Middlewares;

use Utils\Jwt;
use Utils\JsonResponse;

class JwtMiddleware
{
    public static function authenticate(): ?array
    {
        $headers = getallheaders();
        if (empty($headers['Authorization'])) {
            JsonResponse::unauthorized('Token manquant');
            return null;
        }

        // ici tu peux enrichir plus tard
        return ['message' => 'JWT OK'];
    }
}
