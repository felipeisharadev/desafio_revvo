<?php
/** @var array $curso */
/** @var string $csrfToken */

$curso = $curso ?? [];
$csrf  = htmlspecialchars($csrfToken ?? '', ENT_QUOTES);

$id            = (int)($curso['id'] ?? 0);
$nome          = htmlspecialchars($curso['nome'] ?? '', ENT_QUOTES);
$descricao     = htmlspecialchars($curso['descricao'] ?? '', ENT_QUOTES);
$carga_horaria = htmlspecialchars((string)($curso['carga_horaria'] ?? ''), ENT_QUOTES);
$imagemAtual   = isset($curso['imagem']) && $curso['imagem'] !== '' ? htmlspecialchars($curso['imagem'], ENT_QUOTES) : null;

ob_start();
?>
<form action="/cursos/update/<?= $id ?>" method="post" id="form-edit-course" class="modal__form" enctype="multipart/form-data" novalidate>
  <input type="hidden" name="csrf" value="<?= $csrf ?>">

  <div class="form-row">
    <label for="edit_nome">Nome do curso</label>
    <input id="edit_nome" name="nome" type="text" required value="<?= $nome ?>" placeholder="Ex.: PHP Básico">
    <small class="help">Obrigatório.</small>
  </div>

  <div class="form-row">
    <label for="edit_descricao">Descrição</label>
    <textarea id="edit_descricao" name="descricao" rows="3" placeholder="Breve descrição do curso"><?= $descricao ?></textarea>
  </div>

  <div class="form-row">
    <label for="edit_carga_horaria">Carga horária (h)</label>
    <input id="edit_carga_horaria" name="carga_horaria" type="number" min="0" step="1" inputmode="numeric" value="<?= $carga_horaria ?>" placeholder="40">
  </div>

  <div class="form-row">
    <label for="edit_imagem">Imagem do curso (opcional)</label>
    <?php if ($imagemAtual): ?>
      <div class="stack gap-8" style="margin-bottom:.5rem">
        <img src="/<?= $imagemAtual ?>" alt="Imagem atual do curso" style="max-width:180px;height:auto;">
        <small class="help">Enviar um novo arquivo substituirá a imagem atual.</small>
      </div>
    <?php else: ?>
      <small class="help" style="display:block;margin-bottom:.5rem;">Sem imagem atual.</small>
    <?php endif; ?>
    <input id="edit_imagem" name="imagem" type="file" accept="image/*">
    <small class="help">Formatos: JPEG, PNG, WEBP ou GIF. Máx: 3 MB.</small>
  </div>
</form>
<?php
$contentHtml = ob_get_clean();

$actionsHtml = <<<HTML
  <button class="btn btn--ghost" type="button" data-modal-close>Cancelar</button>
  <button class="btn btn--primary" type="submit" form="form-edit-course">Salvar</button>
HTML;

$modal = [
  'id'          => 'modal-edit-course',
  'title'       => 'Editar curso',
  'size'        => 'md',
  'contentHtml' => $contentHtml,
  'actionsHtml' => $actionsHtml,
];

include __DIR__ . '/../components/modal.php';
