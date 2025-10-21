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
        $link  = isset($course['id']) ? "/cursos/" . (int)$course['id'] : ($course['link'] ?? '#');
        $isNew = strtolower($course['nome'] ?? '') === 'php básico';
      ?>

      <div class="course-card">
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
          <h3 class="course-title">
            <?= htmlspecialchars($course['nome'] ?? 'Pellentesque Malesuada') ?>
          </h3>
          <p class="course-description">
            <?= htmlspecialchars($course['descricao'] ?? 'Curabitur blandit tempus porttitor. Nulla vitae elit libero, a pharetra augue.') ?>
          </p>
        </div>

        <a href="<?= htmlspecialchars($link) ?>" class="card-link">
          VER CURSO
        </a>
      </div>
    <?php endforeach; ?>

    <?php if (empty($courses)): ?>
      <p style="grid-column: 1 / -1; color:#666;">Nenhum curso encontrado.</p>
    <?php endif; ?>

    <!-- Botão que abre o modal -->
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
// ---------- Componente de Modal ----------
// Montamos os "slots" content/actions usando partials para não poluir a view

// CONTENT: inclui apenas os CAMPOS (sem o <form>)
ob_start();
include __DIR__ . '/_form_fields.php';
$contentHtml = ob_get_clean();

// ACTIONS: botões (o "Salvar" faz submit do form pelo atributo form="")
$actionsHtml = '
  <button class="btn btn--ghost" type="button" data-modal-close>Cancelar</button>
  <button class="btn btn--primary" type="submit" form="form-add-course">Salvar</button>
';

// Props do componente
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
