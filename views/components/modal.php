<?php
// Componente de Modal isolado
$props = $modal ?? [];
$id    = $props['id']    ?? 'modal-'.uniqid();
$title = $props['title'] ?? '';
$size  = $props['size']  ?? 'md';

$contentHtml = $props['contentHtml'] ?? '';
$actionsHtml = $props['actionsHtml'] ?? '';

$sizeClass = match ($size) {
  'sm' => 'app-modal__dialog--sm',
  'lg' => 'app-modal__dialog--lg',
  default => 'app-modal__dialog--md',
};
?>
<div class="app-modal" id="<?= htmlspecialchars($id) ?>" role="dialog" aria-modal="true" aria-labelledby="<?= htmlspecialchars($id) ?>-title" hidden>
  <div class="app-modal__backdrop" data-modal-close></div>

  <div class="app-modal__dialog <?= $sizeClass ?>" role="document">
    <header class="app-modal__header">
      <h3 id="<?= htmlspecialchars($id) ?>-title"><?= htmlspecialchars($title) ?></h3>
      <button class="app-modal__close" type="button" aria-label="Fechar" data-modal-close>
        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </header>

    <div class="app-modal__content">
      <?= $contentHtml ?>
    </div>

    <?php if ($actionsHtml): ?>
      <footer class="app-modal__actions">
        <?= $actionsHtml ?>
      </footer>
    <?php endif; ?>
  </div>
</div>
