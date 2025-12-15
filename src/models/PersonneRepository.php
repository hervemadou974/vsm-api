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

    /**
     * Récupère une personne par son email (authentification)
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM personnes
             WHERE email = :email
             LIMIT 1"
        );

        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Création d'un patient
     */
    public function create(array $data): ?int
    {
        $stmt = $this->db->prepare("
            INSERT INTO personnes (nom, prenom, date_naissance, vulnerabilite)
            VALUES (:nom, :prenom, :date_naissance, :vulnerabilite)
        ");

        $ok = $stmt->execute([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'date_naissance' => $data['date_naissance'],
            'vulnerabilite' => $data['vulnerabilite']
        ]);

        if (!$ok) {
            return null;
        }

        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupère une personne par son identifiant (contexte patient)
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, nom, prenom, date_naissance, vulnerabilite
             FROM personnes
             WHERE id = :id
             LIMIT 1"
        );

        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Récupère la liste des patients
     */
    public function findAll(): array
    {
        $stmt = $this->db->query(
            "SELECT id, nom, prenom, date_naissance, vulnerabilite
             FROM personnes
             ORDER BY nom, prenom"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les rôles associés à une personne
     * (non implémenté – AT3)
     */
    public function findRolesByPersonneId(int $id): array
    {
        return [];
    }
}
