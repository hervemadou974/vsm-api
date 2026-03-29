<?php

namespace Controllers;

use Utils\JsonResponse;
use Middlewares\JwtMiddleware;
use Models\PersonneRepository;

class PatientController
{
    private PersonneRepository $repo;

    public function __construct()
    {
        $this->repo = new PersonneRepository();
    }

    /**
     * POST /patient
     * Création d'un patient
     */
    public function create(): void
    {
        // Sécurité JWT
        $user = JwtMiddleware::authenticate();
        if (!$user) {
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $nom = $input['nom'] ?? null;
        $prenom = $input['prenom'] ?? null;
        $dateNaissance = $input['date_naissance'] ?? null;
        $vulnerabilite = $input['vulnerabilite'] ?? null;

        if (!$nom || !$prenom) {
            JsonResponse::error("Nom et prénom obligatoires", 422);
            return;
        }

        $patientId = $this->repo->create([
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $dateNaissance,
            'vulnerabilite' => $vulnerabilite
        ]);

        if (!$patientId) {
            JsonResponse::error("Erreur création patient", 500);
            return;
        }

        JsonResponse::success([
            'patient_id' => $patientId
        ], 201);
    }

    /**
     * GET /patients
     * Liste des patients
     */
    public function index(): void
    {
        // Sécurité JWT
        $user = JwtMiddleware::authenticate();
        if (!$user) {
            return;
        }

        $patients = $this->repo->findAll();

        JsonResponse::success($patients);
    }

    /**
     * GET /patient?id=X
     * Fiche patient
     */
    public function show(): void
    {
        // Sécurité JWT
        $user = JwtMiddleware::authenticate();
        if (!$user) {
            return;
        }

        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            JsonResponse::error("Identifiant patient manquant", 400);
            return;
        }

        $patient = $this->repo->findById((int) $id);

        if (!$patient) {
            JsonResponse::notFound("Patient introuvable");
            return;
        }

        JsonResponse::success($patient);
    }
}
