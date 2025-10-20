<?php
// app/Controllers/CursoController.php
namespace App\Controllers;

use App\Interfaces\ViewRendererInterface; // mantenha se você ainda estiver usando a interface
use App\Core\Database;
use App\Core\Request;
use App\Models\Curso;
use Throwable;

class CursoController
{
    private Curso $curso;
    private ViewRendererInterface $renderer;

    public function __construct(ViewRendererInterface $renderer)
    {
        $this->renderer = $renderer;
        $this->curso = new Curso(); // sem passar DB
    }

    public function index(Request $request): string
    {
        $cursos = $this->curso->all();

        return $this->renderer->render('cursos/index', [
            'title'  => 'Lista de Cursos',
            'cursos' => $cursos,
        ]);
    }

    public function create(): string
    {
        return $this->renderer->render('cursos/create', [
            'title' => 'Criar Novo Curso',
        ]);
    }

    public function store(Request $request): string
    {
        try {
            Database::beginTransaction();
            $ultimoId = $this->curso->create($request->body ?? []);
            Database::commit();

            return $this->renderer->render('debug', [
                'message'   => "Curso salvo com sucesso. ID inserido: {$ultimoId}",
                'POST_data' => $request->body ?? [],
            ]);
        } catch (Throwable $e) {
            Database::rollBack();
            http_response_code(500);
            return $this->renderer->render('debug', [
                'message'   => 'Falha ao salvar curso: ' . $e->getMessage(),
                'POST_data' => $request->body ?? [],
            ]);
        }
    }

    public function show(Request $request): string
    {
        $id = 1;
        $curso = $this->curso->find($id);

        return $this->renderer->render('cursos/show', [
            'title' => 'Visualizar Curso',
            'curso' => $curso ?? [],
        ]);
    }
}
