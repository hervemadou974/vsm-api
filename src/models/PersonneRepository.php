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
        $sql = "SELECT * FROM personnes WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $personne = $stmt->fetch();
        return $personne ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM personnes WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $personne = $stmt->fetch();
        return $personne ?: null;
    }

    public function findRolesByPersonneId(int $personneId): array
    {
        $sql = "
            SELECT r.code, r.libelle
            FROM roles r
            INNER JOIN personne_roles pr ON pr.role_id = r.id
            WHERE pr.personne_id = :personne_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personne_id' => $personneId]);

        return $stmt->fetchAll();
    }
}
