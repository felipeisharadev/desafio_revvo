<?php
$modal = [
  'id'          => 'modal-onboarding',
  'title'       => '',
  'size'        => 'md',
  'contentHtml' => '
    <div class="onboarding">
      <h2 class="onboarding__title">EGESTAS TORTOR VULPUTATE</h2>
      <p class="onboarding__desc">
        Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.
        Donec ullamcorper nulla non metus auctor fringilla. Donec sed odio dui. Cras
      </p>
      <div class="onboarding__cta">
        <button class="btn btn--primary onboarding__btn" type="button" data-modal-close>
          INSCREVA-SE
        </button>
      </div>
    </div>
  ',
  'actionsHtml' => '',
];

include __DIR__ . '/modal.php';
