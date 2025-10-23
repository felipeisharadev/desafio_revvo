<?php
ob_start();
include __DIR__ . '/_form_fields.php';
$errors = $errors ?? ($viewData['errors'] ?? []);
?>
  <div class="form-row">
    <label for="imagem">Imagem do curso</label>
    <input id="imagem" name="imagem" type="file" accept="image/*" required>
    <?= !empty($errors['imagem']) ? '<div class="form-error">'.implode('<br>', $errors['imagem']).'</div>' : '' ?>
    <small class="help">Formatos aceitos: JPEG, PNG, WEBP ou GIF. Máx: 3 MB.</small>
  </div>
<?php
$contentFieldsHtml = ob_get_clean();

$csrf = htmlspecialchars($csrfToken, ENT_QUOTES);
$formHtml = <<<HTML
<form action="/cursos" method="post" id="form-add-course" class="modal__form" enctype="multipart/form-data" novalidate>
  <input type="hidden" name="csrf" value="{$csrf}">
  {$contentFieldsHtml}
</form>
HTML;

$actionsHtml = <<<HTML
  <button class="btn btn--ghost" type="button" data-modal-close>Cancelar</button>
  <button class="btn btn--primary" type="submit" form="form-add-course">Salvar</button>
HTML;

$modal = [
  'id'          => 'modal-add-course',
  'title'       => 'Adicionar curso',
  'size'        => 'md',
  'contentHtml' => $formHtml,
  'actionsHtml' => $actionsHtml,
];

include __DIR__ . '/../components/modal.php';
