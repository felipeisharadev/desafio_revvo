<?php
// views/curso/create.php
// Recebe $title

use App\Infrastructure\SimpleViewRenderer as Renderer;
?>

<h2><?php echo Renderer::e($title ?? 'Criar Novo Curso'); ?></h2>

<form method="POST" action="/cursos">
    <fieldset>
        <label for="nome">Nome do Curso</label>
        <input type="text" placeholder="Ex: PHP com DI" name="nome" id="nome" required>

        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao"></textarea>

        <label for="carga_horaria">Carga Horária (horas)</label>
        <input type="number" name="carga_horaria" id="carga_horaria" required>
        
        <input class="button-primary" type="submit" value="Salvar Curso">
    </fieldset>
</form>