<?php
// views/cursos/index.php — listagem de cursos
// Espera variável $cursos (array) vinda do controller
?>
<section>
  <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
    <h1>Cursos</h1>
    <a class="btn" href="/?r=cursos&action=create">Novo curso</a>
  </header>

  <?php if (empty($cursos)): ?>
    <p>Nenhum curso cadastrado.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Imagem</th>
          <th>Título</th>
          <th>Carga</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($cursos as $c): ?>
        <tr>
          <td>
            <?php if (!empty($c['imagem'])): ?>
              <img src="/uploads/cursos/<?= View::e($c['imagem']) ?>" alt="<?= View::e($c['nome']) ?>" width="64">
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
          <td><?= View::e($c['nome']) ?></td>
          <td><?= (int)$c['carga_horaria'] ?>h</td>
          <td>
            <a href="/?r=cursos&action=edit&id=<?= (int)$c['id'] ?>">Editar</a>
            &nbsp;|&nbsp;
            <form style="display:inline" method="post" action="/?r=cursos&action=delete" onsubmit="return confirm('Excluir?');">
              <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
              <button type="submit" class="btn">Excluir</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>
