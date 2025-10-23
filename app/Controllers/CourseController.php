<?php
namespace App\Controllers;

use App\Interfaces\ViewRendererInterface;
use App\Core\Database;
use App\Core\Request;
use App\Models\Course;
use App\Services\Csrf;
use Throwable;

class CourseController
{
    private Course $course;
    private ViewRendererInterface $renderer;
    private Csrf $csrf;

    private const MAX_IMAGE_BYTES = 3 * 1024 * 1024; 
    private const MIME_MAP = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

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

            $courseName     = $this->requireField($request, 'nome');
            $courseDesc     = (string)$request->post('descricao');
            $courseWorkload = $this->optionalInt($request->post('carga_horaria'));

            Database::beginTransaction();

            $courseId = $this->course->create([
                'nome'          => $courseName,
                'descricao'     => $courseDesc,
                'carga_horaria' => $courseWorkload,
                'imagem'        => null,
            ]);

            $relativePath = $this->handleCourseImageUpload($request->file('imagem'), $courseId);
            if ($relativePath !== null) {
                $this->course->updateImage($courseId, $relativePath);
            }

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

    private function requireField(Request $request, string $key): string
    {
        $value = trim((string)$request->post($key));
        if ($value === '') {
            throw new \InvalidArgumentException("Campo obrigatório: {$key}");
        }
        return $value;
    }

    private function optionalInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (int)$value;
    }

    private function handleCourseImageUpload(?array $file, int $courseId): ?string
    {
        if (!$file) {
            return null; 
        }

        $this->assertUploadOk($file);
        $this->assertUploadSize((int)$file['size']);
        [, $extension] = $this->detectImageMimeAndExtension($file['tmp_name']);

        $absoluteDirectory   = $this->ensureCourseDirectory($courseId);
        $filename            = $this->randomName($extension);
        $absoluteDestination = $absoluteDirectory . $filename;

        $this->moveUploaded($file['tmp_name'], $absoluteDestination);

        return $this->relativePath($courseId, $filename);
    }

    private function assertUploadOk(array $file): void
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $code = isset($file['error']) ? (string)$file['error'] : 'desconhecido';
            throw new \RuntimeException('Falha no upload (código ' . $code . ').');
        }
    }

    private function assertUploadSize(int $bytes): void
    {
        if ($bytes > self::MAX_IMAGE_BYTES) {
            throw new \RuntimeException('Arquivo muito grande (máx. 3 MB).');
        }
    }

    private function detectImageMimeAndExtension(string $tmpPath): array
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($tmpPath) ?: '';
        $extension = self::MIME_MAP[$mime] ?? null;

        if (!$extension) {
            throw new \RuntimeException('Formato de imagem não permitido.');
        }
        return [$mime, $extension];
    }

    private function ensureCourseDirectory(int $courseId): string
    {
        $absoluteDirectory = rtrim(ROOT_PATH . '/public/uploads/courses/' . $courseId . '/', DIRECTORY_SEPARATOR);
        if (!is_dir($absoluteDirectory)) {
            mkdir($absoluteDirectory, 0777, true);
        }
        return $absoluteDirectory . DIRECTORY_SEPARATOR;
    }

    private function randomName(string $extension): string
    {
        return bin2hex(random_bytes(16)) . '.' . $extension;
    }

    private function moveUploaded(string $tmpPath, string $absoluteDestination): void
    {
        if (!move_uploaded_file($tmpPath, $absoluteDestination)) {
            throw new \RuntimeException('Não foi possível salvar o arquivo enviado.');
        }
    }

    private function relativePath(int $courseId, string $filename): string
    {
        return 'uploads/courses/' . $courseId . '/' . $filename;
    }
}
