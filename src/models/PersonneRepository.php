<?php

namespace Models;

use Utils\Database;
use PDO;

class PersonneRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM personnes WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

public function findRolesByPersonneId(int $id): array
{
    // Gestion des rôles non implémentée à ce stade
    return [];
}

}
