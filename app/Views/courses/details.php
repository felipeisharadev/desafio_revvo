<?php

ob_start(); ?>
<div class="details-placeholder" style="text-align:center; color:#777; padding:2rem;">
  Selecione um curso para ver os detalhes.
</div>
<?php
$detailsContent = ob_get_clean();

$modal = [
  'id'          => 'modal-details',
  'title'       => 'Detalhes do curso',
  'size'        => 'lg',
  'contentHtml' => $detailsContent,
  'actionsHtml' => '
    <button class="btn btn--ghost" type="button" data-modal-close>Fechar</button>
    <button id="delete-course-btn" class="btn btn--danger" type="button">Excluir</button>
  ',
];

include __DIR__ . '/../components/modal.php';
