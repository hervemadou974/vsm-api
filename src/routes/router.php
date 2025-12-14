<?php

namespace HMadou\VsmApi\Routes;

use HMadou\VsmApi\Utils\JsonResponse;
use HMadou\VsmApi\Controllers\AuthController;

class Router
{
    public function handle(string $uri, string $method): void
    {
        // Normaliser l'URI : enlever les slashs de fin et convertir en minuscule
        $uri = rtrim(strtolower($uri), "/");

        /* -----------------------------------
           Route de test existante
        ----------------------------------- */
        if (strpos($uri, "/test") !== false && $method === 'GET') {
            JsonResponse::success("Router ok !");
            return;
        }

        /* -----------------------------------
           POST /auth/login
        ----------------------------------- */
        if (strpos($uri, "/auth/login") !== false && $method === 'POST') {
            $controller = new AuthController();
            $controller->login();
            return;
        }

        /* -----------------------------------
           GET /auth/me  (route protégée JWT)
        ----------------------------------- */
        if (strpos($uri, "/auth/me") !== false && $method === 'GET') {
            $controller = new AuthController();
            $controller->me();
            return;
        }

        /* -----------------------------------
           404 - Route non trouvée
        ----------------------------------- */
        JsonResponse::notFound();
    }
}
