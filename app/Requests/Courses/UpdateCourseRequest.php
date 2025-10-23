<?php
namespace App\Requests\Courses;

use App\Requests\AbstractFormRequest;

final class UpdateCourseRequest extends AbstractFormRequest
{
    protected function rules(): array
    {
        return [
            'nome'          => 'required|string|max:120',
            'descricao'     => 'nullable|string|max:2000',
            'carga_horaria' => 'required|int|min:1',
            'imagem'        => 'nullable|image|max_bytes:3145728', 
        ];
    }
}
