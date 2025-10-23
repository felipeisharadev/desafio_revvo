<?php
ob_start(); ?>
<div class="js-details-content"></div>
<?php
$detailsContent = ob_get_clean();

$modal = [
  'id'          => 'modal-details',
  'title'       => 'Detalhes do curso',
  'size'        => 'lg',
  'contentHtml' => $detailsContent,
  'actionsHtml' => '
    <button class="btn btn--secondary" type="button" id="edit-course-btn">Editar</button>
    <button id="delete-course-btn" class="btn btn--danger" type="button">Excluir</button>
    <button class="btn btn--ghost" type="button" data-modal-close>Fechar</button>
  ',
];


include __DIR__ . '/../components/modal.php';
