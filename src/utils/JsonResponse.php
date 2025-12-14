<?php

namespace HMadou\VsmApi\Utils;

class JsonResponse
{
    public static function success($data = null, int $status = 200)
    {
        http_response_code($status);

        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
    }

    public static function error(string $message, int $status = 400)
    {
        http_response_code($status);

        echo json_encode([
            "success" => false,
            "error" => $message
        ]);
    }

    public static function unauthorized(string $message = "Accès non autorisé")
    {
        self::error($message, 401);
    }

    public static function notFound(string $message = "Ressource introuvable")
    {
        self::error($message, 404);
    }

    public static function serverError(string $message = "Erreur interne du serveur")
    {
        self::error($message, 500);
    }
}
