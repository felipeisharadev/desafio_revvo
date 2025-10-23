<?php
namespace App\Requests;

use App\Core\Request;

class ValidationException extends \RuntimeException {
    public function __construct(public array $errors) {
        parent::__construct('Validation failed');
    }
}

abstract class AbstractFormRequest
{
    protected Request $request;
    protected array $input = [];
    protected array $files = [];
    protected array $errors = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->input   = $request->post() ?: [];
        $this->files   = $request->file() ?: [];
    }

    abstract protected function rules(): array;

    protected function sanitize(array $in): array
    {
        $out = $in;
        foreach ($out as $k => $v) {
            if (is_string($v)) $out[$k] = trim($v);
        }
        return $out;
    }

    public function validated(): array
    {
        $data = $this->sanitize($this->input);
        $rules = $this->rules();

        foreach ($rules as $field => $ruleLine) {
            $parts = array_filter(array_map('trim', explode('|', $ruleLine)));
            $value = $data[$field] ?? null;

            $nullable = in_array('nullable', $parts, true);

            $isFileField = ($field === 'imagem') || str_starts_with($field, 'file_');
            $file = $isFileField ? ($this->files[$field] ?? null) : null;

            foreach ($parts as $rule) {
                if ($rule === 'nullable') continue;

                if ($rule === 'required') {
                    if ($isFileField) {
                        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                            $this->addError($field, 'Campo obrigatório.');
                        }
                    } else {
                        if ($value === null || $value === '') {
                            $this->addError($field, 'Campo obrigatório.');
                        }
                    }
                }

                if ($rule === 'string' && $value !== null && $value !== '') {
                    if (!is_string($value)) $this->addError($field, 'Deve ser texto.');
                }

                if (str_starts_with($rule, 'max:') && is_string($value)) {
                    $max = (int)substr($rule, 4);
                    if (mb_strlen($value) > $max) $this->addError($field, "Máximo de {$max} caracteres.");
                }

                if ($rule === 'int' && $value !== null && $value !== '') {
                    if (!is_numeric($value)) $this->addError($field, 'Deve ser um número inteiro.');
                    else $data[$field] = (int)$value;
                }

                if (str_starts_with($rule, 'min:') && $value !== null && $value !== '') {
                    $min = (int)substr($rule, 4);
                    if ((int)$value < $min) $this->addError($field, "Valor mínimo é {$min}.");
                }

                if ($rule === 'image') {
                    if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                        $accept = ['image/jpeg','image/png','image/webp','image/gif'];
                        $type   = $file['type'] ?? '';
                        if (!in_array($type, $accept, true)) {
                            $this->addError($field, 'Formato de imagem inválido.');
                        }
                    }
                }

                if (str_starts_with($rule, 'max_bytes:')) {
                    $limit = (int)substr($rule, 10);
                    $size  = (int)($file['size'] ?? 0);
                    if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                        if ($size > $limit) $this->addError($field, 'Arquivo muito grande.');
                    }
                }

                if ($rule === 'url' && $value !== null && $value !== '') {
                    $ok = filter_var($value, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $value);
                    if (!$ok) {
                        $this->addError($field, 'URL inválida. Use http(s)://');
                    }
                }

            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException($this->errors);
        }
        return $data;
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function old(): array
    {
        return $this->input;
    }
}
