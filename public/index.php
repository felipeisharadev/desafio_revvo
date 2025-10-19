<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Core\Bootstrap;
use App\Core\Request;

try {
    $app = (new Bootstrap())->start(); 
    $response = $app->handle(new Request($_SERVER, $_GET, $_POST)); 
    echo $response;

} catch (Throwable $e) {
    http_response_code(500);
    echo "<h1>Erro Crítico do Sistema.</h1><p>Detalhes: " . $e->getMessage() . "</p>";
}