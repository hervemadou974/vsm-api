<?php

namespace Utils;

class JsonResponse
{
    public static function success($data): void
    {
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $data]);
    }

    public static function error(string $msg, int $code): void
    {
        http_response_code($code);
        echo json_encode(['success' => false, 'message' => $msg]);
    }

    public static function unauthorized(string $msg): void
    {
        self::error($msg, 401);
    }

    public static function notFound(): void
    {
        self::error('Route non trouvée', 404);
    }
}
