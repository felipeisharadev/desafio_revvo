<?php
// Body class para remover o espaço do header na home (via SCSS: body.home .header { margin-bottom: 0; })
$pageClass = 'home';

// Slides do hero (ajuste caminhos e textos)
$slides = [
  [
    'image'     => 'https://picsum.photos/id/1/500/200',
    'alt'       => 'Aprenda e cresça',
    'title'     => 'LOREM IPSUM',
    'text'      => 'Aenean lacinia bibendum nulla sed consectetur. Cum sociis natoque penatibus…',
    'cta_href'  => '/cursos/1',
    'cta_text'  => 'Ver curso',
    'cta_label' => 'Ver curso LOREM IPSUM'
  ],
  [
    'image'     => 'https://picsum.photos/id/2/500/200',
    'alt'       => 'Faça seu próximo curso',
    'title'     => 'APRENDA NO SEU RITMO',
    'text'      => 'Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh…',
    'cta_href'  => '/cursos',
    'cta_text'  => 'Explorar',
    'cta_label' => 'Explorar cursos'
  ],
  [
    'image'     => 'https://picsum.photos/id/3/500/200',
    'alt'       => 'Conhecimento prático',
    'title'     => 'DO ZERO AO AVANÇADO',
    'text'      => 'Cursos atualizados e didáticos para acelerar sua carreira.',
    'cta_href'  => '/cursos',
    'cta_text'  => 'Conheça',
    'cta_label' => 'Conheça os cursos'
  ],
];

// Inclui o componente do hero (carousel Bootstrap)
require __DIR__ . '/../partials/hero_carousel.php';
?>

<section class="container" style="margin-top: 2rem;">
  <h2 class="course-section-title">MEUS CURSOS</h2>

  <div class="course-grid">
    <?php if (!empty($cursos) && is_array($cursos)): ?>
      <?php foreach ($cursos as $curso): ?>
        <article class="course-card">
          <div class="course-card__thumb">
            <img src="/assets/img/course-placeholder.jpg" alt="Capa do curso <?= htmlspecialchars($curso['nome'] ?? '') ?>">
          </div>
          <div class="course-card__body">
            <h3 class="course-card__title">
              <?= htmlspecialchars($curso['nome'] ?? 'Conteúdo do Curso') ?>
            </h3>
            <p class="course-card__desc">
              <?= htmlspecialchars($curso['descricao'] ?? '') ?>
            </p>
          </div>
          <div class="course-card__footer">
            <a class="btn-view" href="/cursos/<?= (int)($curso['id'] ?? 0) ?>">VER CURSO</a>
          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="grid-column: 1 / -1; color:#666;">Nenhum curso encontrado.</p>
    <?php endif; ?>

    <!-- Tile "Adicionar Curso" -->
    <a href="/cursos/create" class="add-card">
      <div class="add-card__icon">+</div>
      <div class="add-card__label">ADICIONAR CURSO</div>
    </a>
  </div>
</section>
