<?php

namespace App\Models;

use App\Core\Database;
use PDO;

final class Curso
{
    private ?PDO $db = null;

    private function db(): PDO
    {
        return $this->db ??= Database::conn();
    }

    public function all(): array
    {
        $stmt = $this->db()->query('SELECT * FROM cursos ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM cursos WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO cursos (nome, descricao, carga_horaria)
             VALUES (:nome, :descricao, :carga)'
        );
        $stmt->execute([
            ':nome'      => trim($data['nome'] ?? ''),
            ':descricao' => ($data['descricao'] ?? null),
            ':carga'     => (int)($data['carga_horaria'] ?? 0),
        ]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db()->prepare(
            'UPDATE cursos
                SET nome = :nome, descricao = :descricao, carga_horaria = :carga
              WHERE id = :id'
        );
        $stmt->execute([
            ':id'        => $id,
            ':nome'      => trim($data['nome'] ?? ''),
            ':descricao' => ($data['descricao'] ?? null),
            ':carga'     => (int)($data['carga_horaria'] ?? 0),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db()->prepare('DELETE FROM cursos WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
