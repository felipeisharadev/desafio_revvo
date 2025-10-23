<?php
$errors = $errors ?? ($viewData['errors'] ?? []);
$old    = $old    ?? ($viewData['old']    ?? []);

function old_val(array $old, string $key, mixed $default = ''): string {
  return htmlspecialchars((string)($old[$key] ?? $default), ENT_QUOTES);
}
function field_errors(array $errors, string $key): string {
  if (empty($errors[$key])) return '';
  $items = array_map(fn($m) => '<div class="form-error">'.$m.'</div>', $errors[$key]);
  return implode('', $items);
}
?>
<div class="form-row">
  <label for="nome">Nome do curso</label>
  <input id="nome" name="nome" type="text" required placeholder="Ex.: PHP Básico"
         value="<?= old_val($old, 'nome') ?>">
  <?= field_errors($errors, 'nome') ?>
  <small class="help">Obrigatório.</small>
</div>

<div class="form-row">
  <label for="descricao">Descrição</label>
  <textarea id="descricao" name="descricao" rows="3" placeholder="Breve descrição do curso"><?= old_val($old, 'descricao') ?></textarea>
  <?= field_errors($errors, 'descricao') ?>
</div>

<div class="form-row">
  <label for="carga_horaria">Carga horária (h)</label>
  <input id="carga_horaria" name="carga_horaria" type="number" min="0" step="1" inputmode="numeric" placeholder="40"
         value="<?= old_val($old, 'carga_horaria') ?>">
  <?= field_errors($errors, 'carga_horaria') ?>
</div>
