<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Curso.php';
require_once __DIR__ . '/../services/UploadService.php';

final class CursoController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::get()->pdo();
    }

    public function list(): void
    {
        $cursos = Curso::all($this->pdo);
        View::render('cursos/index', ['cursos' => $cursos]);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome  = trim((string)($_POST['nome'] ?? ''));
            $carga = (int)($_POST['carga_horaria'] ?? 0);
            $desc  = trim((string)($_POST['descricao'] ?? ''));
            $link  = trim((string)($_POST['link'] ?? ''));

            if ($nome === '' || $carga < 0) {
                View::render('cursos/create', ['error' => 'Preencha nome e carga horária válidos.']);
                return;
            }

            $img = UploadService::handle($_FILES['imagem'] ?? null);

            Curso::create($this->pdo, [
                'nome' => $nome,
                'descricao' => $desc,
                'carga_horaria' => $carga,
                'imagem' => $img,
                'link' => $link,
            ]);
            header('Location: /?r=cursos&action=list');
            exit;
        }

        View::render('cursos/create');
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: /?r=cursos&action=list'); exit;
        }

        $curso = Curso::find($this->pdo, $id);
        if (!$curso) {
            header('Location: /?r=cursos&action=list'); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome  = trim((string)($_POST['nome'] ?? ''));
            $carga = (int)($_POST['carga_horaria'] ?? 0);
            $desc  = trim((string)($_POST['descricao'] ?? ''));
            $link  = trim((string)($_POST['link'] ?? ''));

            if ($nome === '' || $carga < 0) {
                View::render('cursos/edit', ['curso' => $curso, 'error' => 'Preencha nome e carga horária válidos.']);
                return;
            }

            // mantém imagem atual se nenhum arquivo novo
            $newImg = UploadService::handle($_FILES['imagem'] ?? null);
            $img = $newImg ?: ($curso['imagem'] ?? null);

            Curso::update($this->pdo, $id, [
                'nome' => $nome,
                'descricao' => $desc,
                'carga_horaria' => $carga,
                'imagem' => $img,
                'link' => $link,
            ]);

            header('Location: /?r=cursos&action=list');
            exit;
        }

        View::render('cursos/edit', ['curso' => $curso]);
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                Curso::delete($this->pdo, $id);
            }
        }
        header('Location: /?r=cursos&action=list');
        exit;
    }
}
