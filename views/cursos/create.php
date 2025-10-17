<?php $error = $error ?? null; ?>
<section>
  <h1>Novo curso</h1>
  <?php if ($error): ?><p style="color:#b00"><?= View::e($error) ?></p><?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="/?r=cursos&action=create">
    <div>
      <label>Nome*</label><br>
      <input type="text" name="nome" required>
    </div>
    <div>
      <label>Descrição</label><br>
      <textarea name="descricao" rows="4"></textarea>
    </div>
    <div>
      <label>Carga horária (h)*</label><br>
      <input type="number" name="carga_horaria" min="0" required>
    </div>
    <div>
      <label>Link (opcional)</label><br>
      <input type="url" name="link" placeholder="https://exemplo.com/curso">
    </div>
    <div>
      <label>Imagem (jpg, png, gif, webp — até 2MB)</label><br>
      <input type="file" name="imagem" accept=".jpg,.jpeg,.png,.gif,.webp">
    </div>
    <br>
    <button class="btn" type="submit">Salvar</button>
    <a href="/?r=cursos&action=list">Cancelar</a>
  </form>
</section>
