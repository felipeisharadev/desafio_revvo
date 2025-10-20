<?php
/** @var array $slides */
$carouselId = 'carouselExampleControlsNoTouching';
?>
<section class="hero">
  <div id="<?= $carouselId ?>" class="carousel slide" data-bs-touch="false" data-bs-interval="6000">
    <div class="carousel-inner hero__viewport">
      <?php foreach ($slides as $i => $s): ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
          <img
            src="<?= htmlspecialchars($s['image']) ?>"
            class="d-block w-100 hero__img"
            alt="<?= htmlspecialchars($s['alt'] ?? ($s['title'] ?? 'Slide '.($i+1))) ?>"
          >
          <div class="hero__overlay">
            <div class="container hero__content">
              <div class="hero__box">
                <?php if (!empty($s['title'])): ?>
                  <h1><?= htmlspecialchars($s['title']) ?></h1>
                <?php endif; ?>

                <?php if (!empty($s['text'])): ?>
                  <p><?= htmlspecialchars($s['text']) ?></p>
                <?php endif; ?>

                <?php if (!empty($s['cta_href'])): ?>
                  <a
                    class="hero__cta"
                    href="<?= htmlspecialchars($s['cta_href']) ?>"
                    aria-label="<?= htmlspecialchars($s['cta_label'] ?? ($s['cta_text'] ?? 'Ação')) ?>"
                  >
                    <?= htmlspecialchars($s['cta_text'] ?? 'Saiba mais') ?>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev" aria-label="Anterior">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next" aria-label="Próximo">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Próximo</span>
    </button>
  </div>
</section>
