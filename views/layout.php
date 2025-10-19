<?php
// views/layout.php
// A variável $title e $viewFile são extraídas da função render()

// Garante que a função 'e' de escape está acessível
use App\Infrastructure\SimpleViewRenderer;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo SimpleViewRenderer::e($title ?? 'CRUD de Cursos'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <style>
        body { margin-top: 20px; }
        .container { max-width: 900px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Gerenciamento de Cursos</h1>
            <nav>
                <a href="/">Listar Cursos</a> | 
                <a href="/cursos/novo">Novo Curso</a>
            </nav>
            <hr>
        </header>

        <main>
            <?php 
                // A variável $viewFile aponta para a view específica (ex: curso/index.php)
                require $viewFile; 
            ?>
        </main>

        <footer>
            <hr>
            <p>&copy; <?php echo date('Y'); ?> Desafio Revvo - MVC</p>
        </footer>
    </div>
</body>
</html>