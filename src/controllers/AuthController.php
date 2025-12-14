<?php

namespace HMadou\VsmApi\Controllers;

use HMadou\VsmApi\Services\AuthService;
use HMadou\VsmApi\Utils\JsonResponse;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }
    
    public function me(): void
{
    $user = \HMadou\VsmApi\Middlewares\JwtMiddleware::authenticate();

    if (!$user) return;

    JsonResponse::success([
        "user_id" => $user['sub'],
        "email"   => $user['email'],
        "role"    => $user['role']
    ]);
}

    public function login(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $email    = $input['email']    ?? null;
        $password = $input['password'] ?? null;

        if (!$email || !$password) {
            JsonResponse::error("Email et mot de passe sont obligatoires.", 422);
            return;
        }

        $token = $this->authService->login($email, $password);

        if ($token === null) {
            JsonResponse::unauthorized("Identifiants invalides.");
            return;
        }

        JsonResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 3600
        ]);
    }
}
