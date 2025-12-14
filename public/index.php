<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Routes\Router;

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();
$router->handle($uri, $method);
