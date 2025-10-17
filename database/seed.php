<?php
declare(strict_types=1);

$root   = __DIR__;
$dbPath = $root . '/database.sqlite';

if (!file_exists($dbPath)) {
    exit("Erro: rode primeiro `php database/migrate.php`\n");
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Garante que a tabela existe
$hasTable = (int)$pdo->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='cursos'")->fetchColumn();
if (!$hasTable) {
    exit("Erro: tabela 'cursos' não existe. Rode migrate.\n");
}

// Evita duplicar seeds
$count = (int)$pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
if ($count > 0) {
    echo "Seed pulada: 'cursos' já possui $count registros.\n";
    exit(0);
}

// Executa todas as seeds
foreach (glob($root . '/seeds/*.sql') as $file) {
    echo "Executando seed: " . basename($file) . PHP_EOL;
    $sql = file_get_contents($file);
    if ($sql === false) {
        fwrite(STDERR, "Erro ao ler $file\n");
        exit(1);
    }
    $pdo->exec($sql);
}
echo "✅ Seeds aplicadas.\n";
