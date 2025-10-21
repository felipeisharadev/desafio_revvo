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
        $id    = (int)($course['id'] ?? 0);
        $nome  = $course['nome'] ?? 'Curso';
        $desc  = $course['descricao'] ?? '';
        $carga = (int)($course['carga_horaria'] ?? 0);
        $link  = $id ? "/cursos/{$id}" : ($course['link'] ?? '#');
        $isNew = strtolower($nome) === 'php básico';

        // JSON seguro para data-attr
        $courseJson = htmlspecialchars(json_encode([
          'id' => $id,
          'nome' => $nome,
          'descricao' => $desc,
          'carga_horaria' => $carga,
          'imagem' => $img,
        ], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
      ?>

      <div class="course-card">
        <?php if ($isNew): ?>
          <div class="tag-new">NOVO</div>
        <?php endif; ?>

        <div class="card-image-wrapper">
          <img
            src="<?= htmlspecialchars($img) ?>"
            alt="Imagem do curso <?= htmlspecialchars($nome) ?>"
            onerror="this.onerror=null; this.src='<?= htmlspecialchars($placeholder_image_url) ?>';"
          >
        </div>

        <div class="card-body">
          <h3 class="course-title"><?= htmlspecialchars($nome) ?></h3>
          <p class="course-description">
            <?= htmlspecialchars($desc ?: 'Sem descrição.') ?>
          </p>
        </div>

        <div class="card-actions">
          <!-- Agora o botão VER CURSO abre o modal de detalhes -->
          <button
            type="button"
            class="card-link"
            data-modal-open="#modal-details"
            data-course='<?= $courseJson ?>'
            aria-haspopup="dialog"
            aria-controls="modal-details">
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
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </svg>
      <span>ADICIONAR CURSO</span>
    </button>

  </div>
</section>

<?php
// ============================================================
// MODAL: CRIAR CURSO
// ============================================================
ob_start();
include __DIR__ . '/_form_fields.php';
$formFields = ob_get_clean();

$modal = [
  'id'          => 'modal-add-course',
  'title'       => 'Adicionar curso',
  'size'        => 'md',
  'contentHtml' => '
    <form action="/cursos" method="post" id="form-add-course" class="app-modal__form" novalidate>
      ' . $formFields . '
    </form>
  ',
  'actionsHtml' => '
    <button class="btn btn--ghost" type="button" data-modal-close>Cancelar</button>
    <button class="btn btn--primary" type="submit" form="form-add-course">Salvar</button>
  ',
];
include __DIR__ . '/../components/modal.php';


// ============================================================
// MODAL: DETALHES DO CURSO
// ============================================================

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
  ',
];
include __DIR__ . '/../components/modal.php';
?>


<!-- ============================================================
     SCRIPT LOCAL PARA PREENCHER O MODAL DE DETALHES
=============================================================== -->
<script>
(function(){
  document.addEventListener('click', function(e){
    const btn = e.target.closest('[data-modal-open="#modal-details"][data-course]');
    if (!btn) return;

    let data;
    try { data = JSON.parse(btn.getAttribute('data-course')); }
    catch (err) { console.warn('Erro ao parsear curso:', err); return; }

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
