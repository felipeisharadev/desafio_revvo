<?php
declare(strict_types=1);

final class Curso
{
    public static function all(PDO $pdo): array
    {
        $st = $pdo->query("SELECT * FROM cursos ORDER BY id DESC");
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(PDO $pdo, int $id): ?array
    {
        $st = $pdo->prepare("SELECT * FROM cursos WHERE id = :id");
        $st->execute([':id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(PDO $pdo, array $data): int
    {
        $st = $pdo->prepare("
            INSERT INTO cursos (nome, descricao, carga_horaria, imagem, link)
            VALUES (:nome, :descricao, :carga_horaria, :imagem, :link)
        ");
        $st->execute([
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'] ?? null,
            ':carga_horaria' => (int)$data['carga_horaria'],
            ':imagem' => $data['imagem'] ?? null,
            ':link' => $data['link'] ?? null,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(PDO $pdo, int $id, array $data): void
    {
        $st = $pdo->prepare("
            UPDATE cursos
               SET nome=:nome, descricao=:descricao, carga_horaria=:carga_horaria, imagem=:imagem, link=:link
             WHERE id=:id
        ");
        $st->execute([
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'] ?? null,
            ':carga_horaria' => (int)$data['carga_horaria'],
            ':imagem' => $data['imagem'] ?? null,
            ':link' => $data['link'] ?? null,
            ':id' => $id,
        ]);
    }

    public static function delete(PDO $pdo, int $id): void
    {
        $st = $pdo->prepare("DELETE FROM cursos WHERE id = :id");
        $st->execute([':id' => $id]);
    }
}
