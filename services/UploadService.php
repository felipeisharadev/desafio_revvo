<?php
declare(strict_types=1);
namespace App\services;

final class UploadService
{
    private const MAX_BYTES = 2_000_000; // ~2MB
    private const ALLOWED_MIME = ['image/jpeg','image/png','image/gif','image/webp'];

    public static function handle(?array $file): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null; // nenhum arquivo enviado
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null; // silencioso para desafio; em prod: trate mensagens
        }

        if ($file['size'] > self::MAX_BYTES) {
            return null;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            return null;
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
            default      => 'bin',
        };

        $name = bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = __DIR__ . '/../public/uploads/cursos/' . $name;

        if (!@move_uploaded_file($file['tmp_name'], $dest)) {
            return null;
        }
        return $name; // salvar este nome no banco
    }
}
