<?php
// views/curso/index.php
// Recebe $title e $cursos (array de cursos)

use App\Infrastructure\SimpleViewRenderer as Renderer;

$titulo = $title ?? 'Lista dsadasde Cursos';
?>

<h2><?php echo Renderer::e($titulo); ?></h2>

<p><a href="/cursos/novo" class="button button-small">Adicionar Novo Curso</a></p>

<?php if (empty($cursos)): ?>
    <p>Nenhum curso cadastrado. Adicione um novo!</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Carga Horária</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td><?php echo Renderer::e($curso['id']); ?></td>
                    <td><?php echo Renderer::e($curso['nome']); ?></td>
                    <td><?php echo Renderer::e($curso['carga_horaria']) . 'h'; ?></td>
                    <td>
                        <a href="/cursos/<?php echo Renderer::e($curso['id']); ?>/visualizar" class="button button-small">Ver</a>
                        <a href="/cursos/<?php echo Renderer::e($curso['id']); ?>/editar" class="button button-small">Editar</a>
                        </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>