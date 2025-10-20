<?php
// Interfaces/DBConnectionInterface.php
namespace App\Interfaces;

interface DBConnectionInterface
{
    public function query(string $sql, array $params = []): array;
    
    public function exec(string $sql, array $params = []): int;

    public function lastInsertId(): int|string;
}