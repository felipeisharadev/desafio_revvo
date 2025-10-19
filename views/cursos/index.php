<?php 
$cursos = $viewData['cursos'] ?? []; 

$banner_image = 'https://placehold.co/1200x400/1F2937/FFFFFF/svg?text=Aprenda+e+Cres%C3%A7a';
$placeholder_image_url = 'https://placehold.co/600x400/6B7280/FFFFFF?text=Conteudo+do+Curso';

?>

<div class="container">
    
    <section class="main-banner">
        <img class="banner-image" src="<?= htmlspecialchars($banner_image) ?>" alt="Banner de Cursos">
        <div class="banner-content">
            <div class="text-box">
                <h1>LOREM IPSUM</h1>
                <p>Aenean lacinia bibendum nulla sed consectetur. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                <a href="#cursos" class="cta-button">VER CURSO</a>
            </div>
        </div>
    </section>

    <section class="course-section">
        <h2 class="course-section-title" id="cursos">MEUS CURSOS</h2>

        <div class="course-grid">
            
            <?php foreach ($cursos as $curso): ?>
                
                <div class="course-card">
                    
                    <?php if (strtolower($curso['nome'] ?? '') === 'php básico'): ?>
                        <div class="tag-new">NOVO</div>
                    <?php endif; ?>

                    <div class="card-image-wrapper">
                        <img 
                            src="<?= htmlspecialchars($curso['imagem'] ?? $placeholder_image_url) ?>" 
                            alt="Imagem do curso <?= htmlspecialchars($curso['nome'] ?? 'Curso') ?>"
                            onerror="this.onerror=null; this.src='<?= htmlspecialchars($placeholder_image_url) ?>';"
                        >
                    </div>

                    <div class="card-body">
                        <h3 class="course-title"><?= htmlspecialchars($curso['nome'] ?? 'Pellentesque Malesuada') ?></h3>
                        <p class="course-description">
                            <?= htmlspecialchars($curso['descricao'] ?? 'Curabitur blandit tempus porttitor. Nulla vitae elit libero, a pharetra augue.') ?>
                        </p>
                    </div>

                    <a href="<?= htmlspecialchars($curso['link'] ?? '#') ?>" class="card-link">
                        VER CURSO
                    </a>
                </div>

            <?php endforeach; ?>

            <a href="/cursos/novo" class="add-course-button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>ADICIONAR CURSO</span>
            </a>
            
        </div>
    </section>
</div>