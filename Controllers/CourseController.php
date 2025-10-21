<?php
// app/Controllers/CourseController.php
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
}
