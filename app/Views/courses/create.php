<?php

ob_start();
include __DIR__ . '/_form_fields.php';
$contentHtml = ob_get_clean();

$actionsHtml = '
  <button class="btn btn--ghost" type="button" data-modal-close>Cancelar</button>
  <button class="btn btn--primary" type="submit" form="form-add-course">Salvar</button>
';

$modal = [
  'id'          => 'modal-add-course',
  'title'       => 'Adicionar curso',
  'size'        => 'md',
  'contentHtml' => '
    <form action="/cursos" method="post" id="form-add-course" class="modal__form" novalidate>
      ' . $contentHtml . '
    </form>
  ',
  'actionsHtml' => $actionsHtml,
];

include __DIR__ . '/../components/modal.php';
