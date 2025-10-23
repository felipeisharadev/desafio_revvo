<?php
$errors   = $errors   ?? ($viewData['errors']   ?? []);
$old      = $old      ?? ($viewData['old']      ?? []);
$defaults = $defaults ?? ($viewData['curso']    ?? []); 

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
  <input id="nome" name="nome" type="text" required
       placeholder="Ex.: PHP Básico"
       value="<?= old_val($old, 'nome', $defaults['nome'] ?? '') ?>">
  <?= field_errors($errors, 'nome') ?>
</div>

<div class="form-row">
  <label for="descricao">Descrição</label>
  <textarea id="descricao" name="descricao" rows="3"
  ><?= old_val($old, 'descricao', $defaults['descricao'] ?? '') ?></textarea>
</div>

<div class="form-row">
  <label for="link">Slideshow (link)</label>
  <input
    id="link"
    name="link"
    type="url"
    inputmode="url"
    placeholder="https://exemplo.com/seu-slideshow"
    pattern="https?://.*"
    value="<?= old_val($old, 'link', $defaults['link'] ?? '') ?>"
  >
  <?= field_errors($errors, 'link') ?>
  <small class="help">Opcional. Use http(s):// (máx. 2048 caracteres).</small>
</div>

<div class="form-row">
  <label for="carga_horaria">Carga horária (h)</label>
  <input id="carga_horaria" name="carga_horaria" type="number" min="0" step="1" inputmode="numeric"
       placeholder="40"
       value="<?= old_val($old, 'carga_horaria', $defaults['carga_horaria'] ?? '') ?>">
</div>
