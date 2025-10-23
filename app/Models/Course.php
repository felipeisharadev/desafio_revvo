<?php
namespace App\Models;

use App\Core\Database;
use PDO;

final class Course
{
    private ?PDO $db = null;

    private function db(): PDO
    {
        return $this->db ??= Database::conn();
    }

    public function all(): array
    {
        $statement = $this->db()->query('SELECT * FROM cursos ORDER BY id DESC');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM cursos WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->db()->prepare(
            'INSERT INTO cursos (nome, descricao, carga_horaria)
             VALUES (:nome, :descricao, :carga)'
        );
        $statement->execute([
            ':nome'      => trim($data['nome'] ?? ''),
            ':descricao' => ($data['descricao'] ?? null),
            ':carga'     => (int)($data['carga_horaria'] ?? 0),
        ]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $statement = $this->db()->prepare(
            'UPDATE cursos
                SET nome = :nome, descricao = :descricao, carga_horaria = :carga
              WHERE id = :id'
        );
        $statement->execute([
            ':id'        => $id,
            ':nome'      => trim($data['nome'] ?? ''),
            ':descricao' => ($data['descricao'] ?? null),
            ':carga'     => (int)($data['carga_horaria'] ?? 0),
        ]);
    }

    public function updateImage(int $id, string $relativePath): void
    {
        $statement = $this->db()->prepare(
            'UPDATE cursos SET imagem = :imagem WHERE id = :id'
        );
        $statement->execute([
            ':imagem' => $relativePath,
            ':id'     => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $statement = $this->db()->prepare('DELETE FROM cursos WHERE id = :id');
        $statement->execute([':id' => $id]);
    }
}
