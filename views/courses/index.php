<?php
// app/Views/courses/index.php

$courses = $courses ?? ($viewData['courses'] ?? []);
$placeholder_image_url = '/assets/imgs/course-placeholder.png';

require __DIR__ . '/../partials/hero_carousel.php';

// flash simples via querystring
$created = isset($_GET['created']) && $_GET['created'] == '1';
?>

<section class="container-wide">
  <h2 class="course-section-title" id="cursos">MEUS CURSOS</h2>
  <hr>

  <?php if ($created): ?>
    <div class="flash flash--success" role="status" aria-live="polite">
      Curso criado com sucesso.
    </div>
  <?php endif; ?>

  <div class="course-grid">
    <?php foreach ($courses as $course): ?>
      <?php
        $img   = $course['imagem']   ?? $placeholder_image_url;
        $link  = isset($course['id']) ? "/cursos/" . (int)$course['id'] : '#';
        $isNew = strtolower($course['nome'] ?? '') === 'php básico';
      ?>

      <div class="course-card" data-course-id="<?= htmlspecialchars($course['id']) ?>">
        <?php if ($isNew): ?>
          <div class="tag-new">NOVO</div>
        <?php endif; ?>

        <div class="card-image-wrapper">
          <img
            src="<?= htmlspecialchars($img) ?>"
            alt="Imagem do curso <?= htmlspecialchars($course['nome'] ?? 'Curso') ?>"
            onerror="this.onerror=null; this.src='<?= htmlspecialchars($placeholder_image_url) ?>';"
          >
        </div>

        <div class="card-body">
          <h3 class="course-title"><?= htmlspecialchars($course['nome'] ?? 'Curso sem nome') ?></h3>
          <p class="course-description"><?= htmlspecialchars($course['descricao'] ?? 'Sem descrição disponível.') ?></p>
        </div>

        <div class="card-actions">
          <button
            class="card-link"
            type="button"
            data-modal-open="#modal-details"
            data-course='<?= json_encode($course, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
            VER CURSO
          </button>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($courses)): ?>
      <p style="grid-column: 1 / -1; color:#666;">Nenhum curso encontrado.</p>
    <?php endif; ?>

    <!-- Botão que abre o modal de criação -->
    <button class="add-course-button" type="button"
            data-modal-open="#modal-add-course"
            aria-haspopup="dialog" aria-controls="modal-add-course">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </svg>
      <span>ADICIONAR CURSO</span>
    </button>
  </div>
</section>

<?php
// -----------------------------------------------------
// MODAL 1: Criar curso
// -----------------------------------------------------
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
?>

<?php
// -----------------------------------------------------
// MODAL 2: Detalhes do curso
// -----------------------------------------------------
ob_start();
?>
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
?>

<script>
(() => {
  let currentCourseId = null;

  // -------------------------------------------------
  // Ao clicar em "VER CURSO", abre o modal de detalhes
  // -------------------------------------------------
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-modal-open="#modal-details"][data-course]');
    if (!btn) return;

    let data;
    try { data = JSON.parse(btn.getAttribute('data-course')); }
    catch (err) { console.warn('Erro ao parsear curso:', err); return; }

    currentCourseId = data.id;

    const modal = document.getElementById('modal-details');
    const content = modal.querySelector('.app-modal__content');

    const html = `
      <article class="course-details">
        <div class="course-details__media">
          <img src="${escapeHtml(data.imagem || '<?= $placeholder_image_url ?>')}"
               alt="Imagem do curso ${escapeHtml(data.nome || '')}"
               onerror="this.onerror=null; this.src='<?= htmlspecialchars($placeholder_image_url, ENT_QUOTES, 'UTF-8') ?>';">
        </div>
        <div class="course-details__body">
          <h4>${escapeHtml(data.nome || 'Curso')}</h4>
          <p class="muted">${escapeHtml(data.descricao || 'Sem descrição disponível.')}</p>
          <dl class="meta">
            <div><dt>Carga horária</dt><dd>${Number(data.carga_horaria || 0)}h</dd></div>
            <div><dt>ID</dt><dd>#${Number(data.id || 0)}</dd></div>
          </dl>
        </div>
      </article>
    `;
    content.innerHTML = html;
  }, true);

  // -------------------------------------------------
  // Botão de EXCLUIR curso (dentro do modal de detalhes)
  // -------------------------------------------------
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('#delete-course-btn');
    if (!btn) return;

    if (!currentCourseId) {
      alert('Curso inválido.');
      return;
    }

    if (!confirm('Tem certeza que deseja excluir este curso?')) return;

    try {
      const response = await fetch(`/cursos/delete/${currentCourseId}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const text = await response.text();
      let result;
      try { result = JSON.parse(text); }
      catch { throw new Error('Resposta não-JSON: ' + text); }

      if (result.success) {
        document.querySelector(`.course-card[data-course-id="${currentCourseId}"]`)?.remove();
        const modal = btn.closest('.app-modal');
        if (modal) {
          modal.hidden = true;
          document.body.style.overflow = '';
        }

        alert('Curso excluído com sucesso.');
        currentCourseId = null;
      } else {
        alert('Erro: ' + (result.error || 'Falha ao excluir.'));
      }
    } catch (err) {
      alert('Erro de conexão: ' + err.message);
    }
  });

  function escapeHtml(str) {
    return String(str)
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'",'&#039;');
  }
})();
</script>
