<?php
namespace App\Controllers;

final class NotFoundController
{
    public function index(): string
    {
        http_response_code(404);
        // Pode devolver JSON ou HTML simples — deixei texto para não interferir no fetch()
        return '404 - Rota não encontrada';
    }
}
