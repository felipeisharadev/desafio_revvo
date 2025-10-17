<?php
declare(strict_types=1);

// public/index.php — único "index" executável pelo navegador

// Núcleo mínimo
require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../core/View.php';
require __DIR__ . '/../core/Bootstrap.php';

// Roteamento simples por query string:
//   /?r=home
//   /?r=cursos&action=list
$router = $_GET['r']      ?? 'home';
$action = $_GET['action'] ?? 'index';

// Despacha para a rota
Bootstrap::dispatch($router, $action);
