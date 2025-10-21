<?php

$courses = $courses ?? ($viewData['courses'] ?? []);
$placeholder_image_url = '/assets/imgs/course-placeholder.png';

require __DIR__ . '/../partials/hero_carousel.php';
?>

<section class="container-wide">
  <h2 class="course-section-title" id="cursos">MEUS CURSOS</h2>
  <hr>
  <div class="course-grid">

    <?php foreach ($courses as $course): ?>
      <?php
        $img = $course['imagem'] ?? $placeholder_image_url;
        $link = isset($course['id']) ? "/cursos/" . (int)$course['id'] : ($course['link'] ?? '#');
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

    <a href="/cursos/create" class="add-course-button">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </svg>
      <span>ADICIONAR CURSO</span>
    </a>

  </div>
</section>

