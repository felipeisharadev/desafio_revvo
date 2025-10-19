<?php
// contracts/DBConnectionInterface.php
namespace App\Contracts;

interface DBConnectionInterface
{
    // Para SELECT (retorna array)
    public function query(string $sql, array $params = []): array;
    
    // Para INSERT/UPDATE/DELETE (retorna int - linhas afetadas)
    public function exec(string $sql, array $params = []): int;

    // Adicional: Essencial para INSERT
    public function lastInsertId(): int|string;
}