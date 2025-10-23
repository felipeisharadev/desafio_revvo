<?php
/** @var array $curso */
$curso     = $curso ?? ($viewData['curso'] ?? []);
$errors    = $errors ?? ($viewData['errors'] ?? []);
$csrfToken = $csrfToken ?? ($viewData['csrfToken'] ?? '');
$defaults  = $curso; // ✅ usado pelo _form_fields
$csrf      = htmlspecialchars($csrfToken, ENT_QUOTES);
$id        = (int)($curso['id'] ?? 0);

ob_start();
?>
<form action="/cursos/update/<?= $id ?>" method="post" id="form-edit-course"
      class="modal__form" enctype="multipart/form-data" novalidate>
  <input type="hidden" name="csrf" value="<?= $csrf ?>">

  <?php include __DIR__ . '/_form_fields.php'; ?>

  <div class="form-row">
    <label for="imagem">Imagem do curso</label>
    <input id="imagem" name="imagem" type="file" accept="image/*">
    <?= !empty($errors['imagem']) ? '<div class="form-error">'.implode('<br>', $errors['imagem']).'</div>' : '' ?>
    <small class="help">Formatos: JPEG, PNG, WEBP ou GIF. Máx: 3 MB. (opcional)</small>
  </div>
</form>
<?php
$content = ob_get_clean();

$modal = [
  'id'          => 'modal-edit-course',
  'title'       => 'Editar curso',
  'size'        => 'md',
  'contentHtml' => $content,
  'actionsHtml' => '
    <button class="btn btn--ghost" type="button" data-modal-close>Cancelar</button>
    <button class="btn btn--primary" type="submit" form="form-edit-course">Salvar</button>
  ',
];

include __DIR__ . '/../components/modal.php';
