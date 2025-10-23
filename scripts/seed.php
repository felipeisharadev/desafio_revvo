<?php
declare(strict_types=1);

/**
 * Runner de seeds (SQLite)
 * - Usa o mesmo caminho de DB das migrations
 * - Aplica todos os .sql em Database/seeds na ordem natural
 * - NÃO abre transação aqui (evita conflito se o .sql já tiver BEGIN/COMMIT)
 */

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

if (!file_exists($dbPath)) {
    exit("Erro: rode primeiro `php scripts/migrate.php`\n");
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sanidade mínima: tabela cursos existe?
    $hasTable = (int)$pdo
        ->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='cursos'")
        ->fetchColumn();
    if (!$hasTable) {
        exit("Erro: tabela 'cursos' não existe. Rode migrate primeiro.\n");
    }

    // Evitar duplicação
    $count = (int)$pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
    if ($count > 0) {
        echo "Seed pulada: 'cursos' já possui $count registros.\n";
        exit(0);
    }

    $seedDir = ROOT_PATH . '/app/Database/seeds';
    if (!is_dir($seedDir)) {
        echo "Nenhuma pasta de seeds encontrada.\n";
        exit(0);
    }

    $files = glob($seedDir . '/*.sql') ?: [];
    natsort($files);

    if (!$files) {
        echo "Nenhuma seed encontrada.\n";
        exit(0);
    }

    foreach ($files as $file) {
        $sql = file_get_contents($file);
        if ($sql === false) {
            throw new RuntimeException("Erro ao ler $file");
        }
        echo "Executando seed: " . basename($file) . PHP_EOL;
        $pdo->exec($sql);
    }

    echo "✅ Seeds aplicadas com sucesso.\n";

} catch (Throwable $e) {
    fwrite(STDERR, "Erro nas seeds: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
