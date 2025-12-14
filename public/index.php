<?php

require_once __DIR__ . '/../vendor/autoload.php';

use HMadou\VsmApi\Routes\Router;

// Récupérer l’URI réelle sans query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Supprimer le chemin jusqu'à /public
$basePath = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
$uri = str_replace($basePath, '', $uri);

// Méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();
$router->handle($uri, $method);
