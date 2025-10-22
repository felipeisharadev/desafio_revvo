<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Desafio Revvo Cursos' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <?= $extraHead ?? '' ?>

    <link rel="stylesheet" href="/assets/css/styles.css">
    <?php
    $bundle = isset($pageClass) ? basename($pageClass) : null;
    if ($bundle) {
        $path = $_SERVER['DOCUMENT_ROOT'] . "/assets/css/{$bundle}.css";
        if (is_file($path)) {
            $ver = filemtime($path);
            echo '<link rel="stylesheet" href="/assets/css/'.$bundle.'.css?v='.$ver.'">';
        }
    }
    ?>


</head>
<body class="<?= isset($pageClass) ? htmlspecialchars($pageClass) : '' ?>">

    <?php
        require __DIR__ . '/partials/header.php';
    ?>

    <main>
        <?php require $viewFile; ?>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $extraScripts ?? '' ?>
<script src="/assets/js/modal.js" defer></script>
</body>

</html>
