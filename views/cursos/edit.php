<?php
// views/curso/edit.php
// Recebe $title e o array $curso (dados atuais)

use App\Infrastructure\SimpleViewRenderer as Renderer;
?>

<h2><?php echo Renderer::e($title ?? 'Editar Curso'); ?></h2>

<form method="POST" action="/cursos/<?php echo Renderer::e($curso['id']); ?>">
    <input type="hidden" name="_method" value="PUT"> <fieldset>
        <label for="nome">Nome do Curso</label>
        <input type="text" name="nome" id="nome" 
               value="<?php echo Renderer::e($curso['nome'] ?? ''); ?>" required>

        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao"><?php echo Renderer::e($curso['descricao'] ?? ''); ?></textarea>

        <label for="carga_horaria">Carga Horária (horas)</label>
        <input type="number" name="carga_horaria" id="carga_horaria" 
               value="<?php echo Renderer::e($curso['carga_horaria'] ?? ''); ?>" required>

        <input class="button-primary" type="submit" value="Atualizar Curso">
    </fieldset>
</form>