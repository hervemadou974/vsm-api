<?php

namespace Routes;

use Controllers\AuthController;
use Controllers\PatientController;
use Utils\JsonResponse;

class Router
{
    public function handle(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = strtolower(rtrim($path, '/'));

        // Base API (important dans ton contexte XAMPP)
        $base = '/intranetv1/web/php/vsm-api/public';

        switch (true) {

            case $path === "$base/test" && $method === 'GET':
                JsonResponse::success('Router ok !');
                return;

            case $path === "$base/auth/login" && $method === 'POST':
                (new AuthController())->login();
                return;

            case $path === "$base/auth/me" && $method === 'GET':
                (new AuthController())->me();
                return;

            // 🔹 GET patient (lecture)
            case $path === "$base/patient" && $method === 'GET':
                (new PatientController())->show();
                return;

            // 🔹 POST patient (création)
            case $path === "$base/patient" && $method === 'POST':
                (new PatientController())->create();
                return;

            // ✅ GET patients (liste)
            case $path === "$base/patients" && $method === 'GET':
                (new PatientController())->index();
                return;
        }

        JsonResponse::notFound();
    }
}

