<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH . '/vendor/autoload.php';

use App\Core\Bootstrap;
use App\Core\Request;

try {
    $app = (new Bootstrap())->start();
    echo $app->handle(new Request($_SERVER, $_GET, $_POST));
} catch (Throwable $e) {
    http_response_code(500);
    echo "<h1>Erro Crítico do Sistema.</h1><p>Detalhes: " . htmlspecialchars($e->getMessage()) . "</p>";
}
