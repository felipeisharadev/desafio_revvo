<?php

$pageClass = 'home';
$cursos = $cursos ?? ($viewData['cursos'] ?? []);
$placeholder_image_url = 'https://placehold.co/600x400/6B7280/FFFFFF?text=Conteudo+do+Curso';

$slides = [
  [
    'image'     => 'https://picsum.photos/id/1/1600/300',
    'alt'       => 'Aprenda e cresça',
    'title'     => 'LOREM IPSUM',
    'text'      => 'Aenean lacinia bibendum nulla sed consectetur. Cum sociis natoque penatibus…',
    'cta_href'  => '/cursos/1',
    'cta_text'  => 'Ver curso',
    'cta_label' => 'Ver curso LOREM IPSUM'
  ],
  [
    'image'     => 'https://picsum.photos/id/2/1600/300',
    'alt'       => 'Faça seu próximo curso',
    'title'     => 'APRENDA NO SEU RITMO',
    'text'      => 'Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh…',
    'cta_href'  => '/cursos',
    'cta_text'  => 'Explorar',
    'cta_label' => 'Explorar cursos'
  ],
  [
    'image'     => 'https://picsum.photos/id/3/1600/300',
    'alt'       => 'Conhecimento prático',
    'title'     => 'DO ZERO AO AVANÇADO',
    'text'      => 'Cursos atualizados e didáticos para acelerar sua carreira.',
    'cta_href'  => '/cursos',
    'cta_text'  => 'Conheça',
    'cta_label' => 'Conheça os cursos'
  ],
];

require __DIR__ . '/../partials/hero_carousel.php';
?>

<section class="container" style="margin-top: 2rem;">
  <h2 class="course-section-title" id="cursos">MEUS CURSOS</h2>

  <div class="course-grid">

    <?php foreach ($cursos as $curso): ?>
      <?php
        // imagem do curso (se não tiver no banco, usa placeholder)
        $img = $curso['imagem'] ?? $placeholder_image_url;

        // link preferencial: /cursos/{id} se houver id; senão, usa 'link' (se existir) ou '#'
        $link = isset($curso['id']) ? "/cursos/" . (int)$curso['id'] : ($curso['link'] ?? '#');

        // marca "NOVO" para um nome específico (ex.: "PHP básico"), como no seu exemplo anterior
        $isNew = strtolower($curso['nome'] ?? '') === 'php básico';
      ?>

      <div class="course-card">
        <?php if ($isNew): ?>
          <div class="tag-new">NOVO</div>
        <?php endif; ?>

        <div class="card-image-wrapper">
          <img
            src="<?= htmlspecialchars($img) ?>"
            alt="Imagem do curso <?= htmlspecialchars($curso['nome'] ?? 'Curso') ?>"
            onerror="this.onerror=null; this.src='<?= htmlspecialchars($placeholder_image_url) ?>';"
          >
        </div>

        <div class="card-body">
          <h3 class="course-title">
            <?= htmlspecialchars($curso['nome'] ?? 'Pellentesque Malesuada') ?>
          </h3>
          <p class="course-description">
            <?= htmlspecialchars($curso['descricao'] ?? 'Curabitur blandit tempus porttitor. Nulla vitae elit libero, a pharetra augue.') ?>
          </p>
        </div>

        <a href="<?= htmlspecialchars($link) ?>" class="card-link">
          VER CURSO
        </a>
      </div>
    <?php endforeach; ?>

    <?php if (empty($cursos)): ?>
      <p style="grid-column: 1 / -1; color:#666;">Nenhum curso encontrado.</p>
    <?php endif; ?>

    <!-- Tile "Adicionar Curso" -->
    <a href="/cursos/create" class="add-course-button">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </svg>
      <span>ADICIONAR CURSO</span>
    </a>

  </div>
</section>
