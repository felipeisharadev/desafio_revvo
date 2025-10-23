<?php
declare(strict_types=1);

error_reporting(E_ALL);

define('ROOT_PATH', dirname(__DIR__));
$configFile = ROOT_PATH . '/app/Database/config.database.php';

$dbPath = ROOT_PATH . '/app/Database/database.sqlite';
if (file_exists($configFile)) {
    $config = require $configFile;
    if (!empty($config['database_path'])) {
        $dbPath = $config['database_path'];
    }
}

@mkdir(dirname($dbPath), 0775, true);
@mkdir(ROOT_PATH . '/public/uploads/cursos', 0775, true);

if (!file_exists($dbPath)) {
    touch($dbPath);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("PRAGMA foreign_keys = ON;");

    $migDir = ROOT_PATH . '/app/Database/migrations';
    if (!is_dir($migDir)) {
        fwrite(STDERR, "Pasta de migrations não encontrada: $migDir\n");
        exit(1);
    }

    $files = glob($migDir . '/*.sql') ?: [];
    natsort($files);

    if (!$files) {
        echo "Nenhuma migration encontrada.\n";
        exit(0);
    }

    foreach ($files as $file) {
        $sql = file_get_contents($file);
        if ($sql === false) {
            throw new RuntimeException("Erro ao ler $file");
        }
        echo "Executando migration: " . basename($file) . PHP_EOL;
        $pdo->exec($sql);
    }

    echo "✅ Migrations aplicadas com sucesso em: $dbPath\n";

} catch (Throwable $e) {
    fwrite(STDERR, "Erro nas migrations: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
