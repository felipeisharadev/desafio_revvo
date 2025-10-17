<?php
$curso = $curso ?? null;
if (!$curso) { echo "<p>Curso não encontrado.</p>"; return; }
$error = $error ?? null;
?>
<section>
  <h1>Editar curso #<?= (int)$curso['id'] ?></h1>
  <?php if ($error): ?><p style="color:#b00"><?= View::e($error) ?></p><?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="/?r=cursos&action=edit&id=<?= (int)$curso['id'] ?>">
    <div>
      <label>Nome*</label><br>
      <input type="text" name="nome" value="<?= View::e($curso['nome']) ?>" required>
    </div>
    <div>
      <label>Descrição</label><br>
      <textarea name="descricao" rows="4"><?= View::e((string)$curso['descricao']) ?></textarea>
    </div>
    <div>
      <label>Carga horária (h)*</label><br>
      <input type="number" name="carga_horaria" min="0" value="<?= (int)$curso['carga_horaria'] ?>" required>
    </div>
    <div>
      <label>Link (opcional)</label><br>
      <input type="url" name="link" value="<?= View::e((string)$curso['link']) ?>">
    </div>
    <div>
      <label>Imagem atual</label><br>
      <?php if (!empty($curso['imagem'])): ?>
        <img src="/uploads/cursos/<?= View::e($curso['imagem']) ?>" alt="" width="96">
      <?php else: ?> — <?php endif; ?>
    </div>
    <div>
      <label>Nova imagem (opcional)</label><br>
      <input type="file" name="imagem" accept=".jpg,.jpeg,.png,.gif,.webp">
    </div>
    <br>
    <button class="btn" type="submit">Salvar</button>
    <a href="/?r=cursos&action=list">Cancelar</a>
  </form>
</section>
