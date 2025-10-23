<?php
$modal = [
  'id'          => 'modal-onboarding',
  'title'       => 'Bem-vindo(a)!',
  'size'        => 'sm',
  'contentHtml' => '
    <div class="onboarding-content">
      <p style="margin:0 0 8px 0;">Este é um tour rápido da área de cursos.</p>
      <ul style="padding-left:18px; margin:0 0 12px 0;">
        <li>Veja seus cursos no grid.</li>
        <li>Use “Ver Curso” para detalhes.</li>
        <li>Adicione novos cursos no botão “+”.</li>
      </ul>
      <small class="muted">Dica: você pode fechar com a tecla Esc.</small>
    </div>
  ',
  'actionsHtml' => '
    <button class="btn btn--primary" type="button" data-modal-close>Entendi</button>
  ',
];

include __DIR__ . '/modal.php';
