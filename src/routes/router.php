<?php

namespace Routes;

use Controllers\AuthController;
use Utils\JsonResponse;

class Router
{
   public function handle(string $uri, string $method): void
{
    $uri = strtolower(parse_url($uri, PHP_URL_PATH));

    if (str_ends_with($uri, '/test') && $method === 'GET') {
        JsonResponse::success('Router ok !');
        return;
    }

    if (str_ends_with($uri, '/auth/login') && $method === 'POST') {
        (new AuthController())->login();
        return;
    }

    if (str_ends_with($uri, '/auth/me') && $method === 'GET') {
        (new AuthController())->me();
        return;
    }

    JsonResponse::notFound();
}

}
