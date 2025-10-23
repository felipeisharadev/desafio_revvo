<?php
namespace App\Support;

final class Upload
{
    public const MAX_BYTES = 3 * 1024 * 1024;
    public const MIME_MAP = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    public static function saveCourseImage(array $file, int $courseId): string
    {
        // Pré-condições: validações de required/mime/tamanho já ocorreram no Request.
        self::assertOk($file);

        [$mime, $ext] = self::detectMimeAndExt($file['tmp_name']);
        $dir = self::ensureCourseDirectory($courseId);
        $filename = self::randomName($ext);
        $dest = $dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \RuntimeException('Não foi possível salvar o arquivo enviado.');
        }

        return self::relativePath($courseId, $filename);
    }

    public static function detectMimeAndExt(string $tmpPath): array
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($tmpPath) ?: '';
        $ext   = self::MIME_MAP[$mime] ?? null;
        if (!$ext) {
            throw new \RuntimeException('Formato de imagem não permitido.');
        }
        return [$mime, $ext];
    }

    public static function ensureCourseDirectory(int $courseId): string
    {
        $absoluteDirectory = rtrim(ROOT_PATH . '/public/uploads/courses/' . $courseId . '/', DIRECTORY_SEPARATOR);
        if (!is_dir($absoluteDirectory)) {
            mkdir($absoluteDirectory, 0777, true);
        }
        return $absoluteDirectory . DIRECTORY_SEPARATOR;
    }

    public static function randomName(string $ext): string
    {
        return bin2hex(random_bytes(16)) . '.' . $ext;
    }

    public static function relativePath(int $courseId, string $filename): string
    {
        return 'uploads/courses/' . $courseId . '/' . $filename;
    }

    private static function assertOk(array $file): void
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $code = isset($file['error']) ? (string)$file['error'] : 'desconhecido';
            throw new \RuntimeException('Falha no upload (código ' . $code . ').');
        }
    }
}
