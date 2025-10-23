<?php
/** @var array  $curso */
/** @var string $csrfToken */
/** @var array  $errors (opcional) */
/** @var array  $old    (opcional) */

$curso      = $curso ?? ($viewData['curso'] ?? []);
$errors     = $errors ?? ($viewData['errors'] ?? []);
$old        = $old    ?? ($viewData['old']    ?? []);
$csrfToken  = $csrfToken ?? ($viewData['csrfToken'] ?? '');

$id            = (int)($curso['id'] ?? 0);
$nomeBase      = (string)($curso['nome'] ?? '');
$descBase      = (string)($curso['descricao'] ?? '');
$cargaBase     = (string)($curso['carga_horaria'] ?? '');
$imagemAtual   = isset($curso['imagem']) && $curso['imagem'] !== '' ? (string)$curso['imagem'] : null;

function old_val(array $old, string $key, mixed $fallback = ''): string {
  return htmlspecialchars((string)($old[$key] ?? $fallback), ENT_QUOTES);
}
function field_errors(array $errors, string $key): string {
  if (empty($errors[$key])) return '';
  $items = array_map(fn($m) => '<div class="form-error">'.$m.'</div>', $errors[$key]);
  return implode('', $items);
}

ob_start();
?>
<form action="/cursos/update/<?= $id ?>" method="post" id="form-edit-course" class="modal__form" enctype="multipart/form-data" novalidate>
  <input type="hidden" name="csrf" value="<?= htmlspecialchars((string)$csrfToken, ENT_QUOTES) ?>">

  <div class="form-row">
    <label for="edit_nome">Nome do curso</label>
    <input
      id="edit_nome"
      name="nome"
      type="text"
      required
      placeholder="Ex.: PHP Básico"
      value="<?= old_val($old, 'nome', $nomeBase) ?>"
    >
    <?= field_errors($errors, 'nome') ?>
    <small class="help">Obrigatório. Máximo 120 caracteres.</small>
  </div>

  <div class="form-row">
    <label for="edit_descricao">Descrição</label>
    <textarea
      id="edit_descricao"
      name="descricao"
      rows="3"
      placeholder="Breve descrição do curso"
    ><?= old_val($old, 'descricao', $descBase) ?></textarea>
    <?= field_errors($errors, 'descricao') ?>
    <small class="help">Opcional. Até 2000 caracteres.</small>
  </div>

  <div class="form-row">
    <label for="edit_carga_horaria">Carga horária (h)</label>
    <input
      id="edit_carga_horaria"
      name="carga_horaria"
      type="number"
      min="0"
      step="1"
      inputmode="numeric"
      placeholder="40"
      value="<?= old_val($old, 'carga_horaria', $cargaBase) ?>"
    >
    <?= field_errors($errors, 'carga_horaria') ?>
  </div>

  <div class="form-row">
    <label for="edit_imagem">Imagem do curso (opcional)</label>

    <?php if ($imagemAtual): ?>
      <div class="stack gap-8" style="margin-bottom:.5rem">
        <img src="<?= htmlspecialchars($imagemAtual, ENT_QUOTES) ?>" alt="Imagem atual do curso" style="max-width:180px;height:auto;">
        <small class="help">Enviar um novo arquivo substituirá a imagem atual.</small>
      </div>
    <?php else: ?>
      <small class="help" style="display:block;margin-bottom:.5rem;">Sem imagem atual.</small>
    <?php endif; ?>

    <input id="edit_imagem" name="imagem" type="file" accept="image/*">
    <?= field_errors($errors, 'imagem') ?>
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
