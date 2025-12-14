<?php

namespace Services;

use Models\PersonneRepository;
use Utils\Jwt;

class AuthService
{
    private PersonneRepository $personneRepository;

    public function __construct()
    {
        $this->personneRepository = new PersonneRepository();
    }

    public function login(string $email, string $password): array
    {
        $personne = $this->personneRepository->findByEmail($email);

        if (!$personne || !password_verify($password, $personne['mot_de_passe'])) {
            return [
                'success' => false,
                'message' => 'Identifiants invalides'
            ];
        }

        $roles = $this->personneRepository->findRolesByPersonneId($personne['id']);

        $token = Jwt::generate([
            'id'    => $personne['id'],
            'email' => $personne['email'],
            'roles' => array_column($roles, 'code')
        ]);

        return [
            'success' => true,
            'token'   => $token
        ];
    }
}
