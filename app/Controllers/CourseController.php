<?php
namespace App\Controllers;

use App\Interfaces\ViewRendererInterface; // mantenha se você ainda estiver usando a interface
use App\Core\Database;
use App\Core\Request;
use App\Models\Course;
use Throwable;

class CourseController
{
    private Course $course;
    private ViewRendererInterface $renderer;

    public function __construct(ViewRendererInterface $renderer)
    {
        $this->renderer = $renderer;
        $this->course = new Course();
    }

    public function index(Request $request): string
    {
        $cursoModel = new Course();
        $courses = $cursoModel->all();

        return $this->renderer->render('courses/index', [
            'pageTitle' => 'Meus Cursos',
            'pageClass' => 'courses', 
            'courses'    => $courses,
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
            $data = $request->body;
            if (empty(trim($data['nome'] ?? ''))) {
                throw new Exception('O nome é obrigatório');
            }

            Database::beginTransaction();
            $id = $this->course->create($data);
            Database::commit();

            header('Location: /cursos?created=1');
            exit;
        } catch (Throwable $e) {
            Database::rollBack();
            http_response_code(500);
            return $this->renderer->render('debug', [
                'message' => 'Erro: ' . $e->getMessage(),
            ]);
        }
    }


    public function show(Request $request): string
    {
        $id = 1;
        $curso = $this->course->find($id);

        return $this->renderer->render('cursos/show', [
            'title' => 'Visualizar Curso',
            'curso' => $curso ?? [],
        ]);
    }

public function delete(\App\Core\Request $request): void
{
    header('Content-Type: application/json; charset=utf-8');

    try {
        // 1) tenta pegar via $request->params['id'] se seu Application preencher
        $id = isset($request->params['id']) ? (int)$request->params['id'] : 0;

        // 2) fallback: extrai do path (último segmento de /cursos/delete/{id})
        if ($id <= 0) {
            $path = strtok($request->server['REQUEST_URI'] ?? '/', '?') ?: '/';
            $segments = array_values(array_filter(explode('/', $path), 'strlen'));
            $last = end($segments);
            $id = (int)$last;
        }

        if ($id <= 0) {
            throw new \Exception('ID inválido.');
        }

        \App\Core\Database::beginTransaction();
        $this->course->delete($id);
        \App\Core\Database::commit();

        echo json_encode(['success' => true, 'message' => 'Curso excluído com sucesso']);
    } catch (\Throwable $e) {
        \App\Core\Database::rollBack();
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}


}
