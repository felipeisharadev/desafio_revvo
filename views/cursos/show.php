<?php
// views/curso/show.php
// Recebe $title e o array $curso (dados do curso)

use App\Infrastructure\SimpleViewRenderer as Renderer;
?>

<h2>Detalhes do Curso: <?php echo Renderer::e($curso['nome'] ?? 'Curso Não Encontrado'); ?></h2>

<?php if (!empty($curso)): ?>
    <dl>
        <dt>ID:</dt>
        <dd><?php echo Renderer::e($curso['id']); ?></dd>
        
        <dt>Descrição:</dt>
        <dd><?php echo Renderer::e($curso['descricao']); ?></dd>
        
        <dt>Carga Horária:</dt>
        <dd><?php echo Renderer::e($curso['carga_horaria']); ?> horas</dd>
        
        <dt>Criado em:</dt>
        <dd><?php echo Renderer::e($curso['criado_em']); ?></dd>
    </dl>
    
    <a href="/cursos/<?php echo Renderer::e($curso['id']); ?>/editar" class="button">Editar</a>
    <a href="/" class="button">Voltar para a Lista</a>

<?php else: ?>
    <p>O curso solicitado não foi encontrado.</p>
<?php endif; ?>