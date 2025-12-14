<?php

namespace Services;

use Models\PersonneRepository;
use Utils\Jwt;

class AuthService
{
    private PersonneRepository $repo;

    public function __construct()
    {
        $this->repo = new PersonneRepository();
    }

    public function login(string $email, string $password): array
    {
        $user = $this->repo->findByEmail($email);

        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            return [
                'success' => false,
                'message' => 'Identifiants invalides'
            ];
        }

        $roles = $this->repo->findRolesByPersonneId($user['id']);

        $token = Jwt::generate([
            'id' => $user['id'],
            'email' => $user['email'],
            'roles' => array_column($roles, 'code')
        ]);

        return [
            'success' => true,
            'token' => $token
        ];
    }
}
