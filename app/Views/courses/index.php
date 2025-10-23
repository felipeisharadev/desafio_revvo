<?php
$courses                = $courses ?? ($viewData['courses'] ?? []);
$csrfToken              = $csrfToken ?? ($viewData['csrfToken'] ?? null);
$placeholder_image_url  = '/assets/imgs/course-placeholder.png';

require __DIR__ . '/../partials/hero_carousel.php';

$created = isset($_GET['created']) && $_GET['created'] == '1';
?>

<section class="container-wide" data-placeholder-img="<?= htmlspecialchars($placeholder_image_url, ENT_QUOTES) ?>">
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

require __DIR__ . '/create.php';
require __DIR__ . '/details.php';


?>

<?php
require __DIR__ . '/../components/onboarding_modal.php';
?>

<script src="/assets/js/onboarding.js" defer></script>
<script src="/assets/js/courses.js" defer></script>

