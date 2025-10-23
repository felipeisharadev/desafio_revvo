<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH . '/vendor/autoload.php';

use App\Core\Bootstrap;
use App\Core\Request;

$request = new Request($_SERVER, $_GET, $_POST, $_FILES);

try {
    $app = (new Bootstrap())->start();
    echo $app->handle($request);
} catch (Throwable $e) {
    http_response_code(500);

    $accept = $request->server['HTTP_ACCEPT'] ?? '';
    $xhr    = strtolower($request->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    $wantsJson = $xhr || str_contains($accept, 'application/json');

    if ($wantsJson) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error'   => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo "<h1>Erro Crítico do Sistema.</h1><p>Detalhes: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
