<?php

namespace Controllers;

use Services\AuthService;
use Utils\JsonResponse;
use Middlewares\JwtMiddleware;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['email']) || empty($input['password'])) {
            JsonResponse::error('Email et mot de passe obligatoires', 422);
            return;
        }

        $result = $this->authService->login($input['email'], $input['password']);

        if (!$result['success']) {
            JsonResponse::unauthorized($result['message']);
            return;
        }

        JsonResponse::success([
            'token' => $result['token'],
            'type'  => 'Bearer'
        ]);
    }

    public function me(): void
    {
        $user = JwtMiddleware::authenticate();
        if (!$user) return;

        JsonResponse::success($user);
    }
}
