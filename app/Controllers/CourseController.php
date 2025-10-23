<?php
namespace App\Controllers;

use App\Interfaces\ViewRendererInterface;
use App\Core\Database;
use App\Core\Request;
use App\Models\Course;
use App\Services\Csrf;
use App\Requests\ValidationException;
use App\Requests\Courses\StoreCourseRequest;
use App\Requests\Courses\UpdateCourseRequest;
use App\Support\Upload;

use Throwable;

class CourseController
{
    private Course $course;
    private ViewRendererInterface $renderer;
    private Csrf $csrf;

    public function __construct(ViewRendererInterface $renderer)
    {
        $this->renderer = $renderer;
        $this->course   = new Course();
        $this->csrf     = new Csrf(); 
    }

    public function index(\App\Core\Request $request): string
    {
        $courses   = $this->course->all();
        $csrfToken = $this->csrf->token(); 

        return $this->renderer->render('courses/index', [
            'pageTitle' => 'Meus Cursos',
            'pageClass' => 'courses',
            'courses'   => $courses,
            'csrfToken' => $csrfToken,     
        ]);
    }

    public function create(Request $request): string
    {
        $csrfToken = $this->csrf->token();

        return $this->renderer->render('cursos/create', [
            'title'     => 'Criar Novo Curso',
            'csrfToken' => $csrfToken,
        ]);
    }

    public function store(Request $request): string
    {
        try {
            $this->csrf->assertValid($request->post('csrf'));

            $form = new StoreCourseRequest($request);
            $data = $form->validated();

            Database::beginTransaction();

            $courseId = $this->course->create([
                'nome'          => $data['nome'] ?? '',
                'descricao'     => $data['descricao'] ?? null,
                'carga_horaria' => $data['carga_horaria'] ?? null,
                'link'          => $data['link'] ?? null,
                'imagem'        => null,
            ]);

            $file = $form->file('imagem');
            if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $relativePath = Upload::saveCourseImage($file, $courseId);
                $this->course->updateImage($courseId, $relativePath);
            }

            Database::commit();

            if (strtolower($request->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'redirect' => '/cursos?created=1']);
                exit;
            }

            header('Location: /cursos?created=1');
            exit;

        } catch (ValidationException $ve) {
            http_response_code(422);
            $csrfToken = $this->csrf->token();
            return $this->renderer->render('courses/create', [
                'title'     => 'Criar Novo Curso',
                'csrfToken' => $csrfToken,
                'errors'    => $ve->errors,
                'old'       => $form->old(),
            ]);
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
        $id    = 1;
        $curso = $this->course->find($id);

        return $this->renderer->render('cursos/show', [
            'title' => 'Visualizar Curso',
            'curso' => $curso ?? [],
        ]);
    }

    public function delete(Request $request): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $id = isset($request->params['id']) ? (int)$request->params['id'] : 0;

            if ($id <= 0) {
                $path     = strtok($request->server['REQUEST_URI'] ?? '/', '?') ?: '/';
                $segments = array_values(array_filter(explode('/', $path), 'strlen'));
                $last     = end($segments);
                $id       = (int)$last;
            }

            if ($id <= 0) {
                throw new \Exception('ID inválido.');
            }

            Database::beginTransaction();
            $this->course->delete($id);
            Database::commit();

            echo json_encode(['success' => true, 'message' => 'Curso excluído com sucesso']);
        } catch (Throwable $e) {
            Database::rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    public function edit(Request $request): string
    {

        $id = isset($request->params['id']) ? (int)$request->params['id'] : 0;

        if ($id <= 0) {
            $path     = strtok($request->server['REQUEST_URI'] ?? '/', '?') ?: '/';
            $segments = array_values(array_filter(explode('/', $path), 'strlen'));
            $last     = end($segments);
            $id       = (int)$last;
        }

        if ($id <= 0) {
            http_response_code(400);
            return $this->renderer->render('debug', [
                'message' => 'ID inválido para edição.'
            ]);
        }

        $curso = $this->course->find($id);
        if (!$curso) {
            http_response_code(404);
            return $this->renderer->render('debug', [
                'message' => 'Curso não encontrado.'
            ]);
        }

        $csrfToken = $this->csrf->token();

        return $this->renderer->render('courses/edit', [
            'title'     => 'Editar Curso',
            'curso'     => $curso,
            'csrfToken' => $csrfToken,
        ]);
    }

    public function update(Request $request): string
    {
        try {
            $this->csrf->assertValid($request->post('csrf'));

            // ID via rota (mantendo seu fallback defensivo)
            $id = isset($request->params['id']) ? (int)$request->params['id'] : 0;
            if ($id <= 0) {
                $path     = strtok($request->server['REQUEST_URI'] ?? '/', '?') ?: '/';
                $segments = array_values(array_filter(explode('/', $path), 'strlen'));
                $last     = end($segments);
                $id       = (int)$last;
            }
            if ($id <= 0) {
                throw new \InvalidArgumentException('ID inválido.');
            }

            $cursoAtual = $this->course->find($id);
            if (!$cursoAtual) {
                throw new \RuntimeException('Curso não encontrado.');
            }

            $form = new UpdateCourseRequest($request);
            $data = $form->validated();

            Database::beginTransaction();

            $this->course->update($id, [
                'nome'          => $data['nome'] ?? '',
                'descricao'     => $data['descricao'] ?? null,
                'carga_horaria' => $data['carga_horaria'] ?? null,
                'link'          => $data['link'] ?? null,
            ]);

            $file = $form->file('imagem');
            if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $relativePath = Upload::saveCourseImage($file, $id);
                $this->course->updateImage($id, $relativePath);
            }

            Database::commit();

            if (strtolower($request->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'redirect' => '/cursos?updated=1']);
                exit;
            }

            header('Location: /cursos?updated=1');
            exit;

        } catch (ValidationException $ve) {
            http_response_code(422);
            $csrfToken = $this->csrf->token();
            return $this->renderer->render('courses/edit', [
                'title'     => 'Editar Curso',
                'curso'     => $cursoAtual ?? [],
                'csrfToken' => $csrfToken,
                'errors'    => $ve->errors,
                'old'       => $form->old(),
            ]);
        } catch (Throwable $e) {
            Database::rollBack();
            http_response_code(500);
            return $this->renderer->render('debug', [
                'message' => 'Erro ao atualizar: ' . $e->getMessage(),
            ]);
        }
    }

}
