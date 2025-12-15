<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Routes\Router;

$router = new Router();
$router->handle($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
