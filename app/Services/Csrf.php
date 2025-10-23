<?php
namespace App\Services;

final class Csrf
{
    public function token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function assertValid(?string $token): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $expected = $_SESSION['csrf_token'] ?? '';
        if (!$token || !$expected || !hash_equals($expected, $token)) {
            throw new \RuntimeException('CSRF token inválido.');
        }
    }
}
