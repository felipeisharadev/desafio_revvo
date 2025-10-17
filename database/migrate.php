<?php
declare(strict_types=1);

$root   = __DIR__;
$dbPath = $root . '/database.sqlite';

// cria docroot de uploads se faltar (qualquer primeira execução)
@mkdir(dirname(__DIR__) . '/public/uploads/cursos', 0777, true);

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Executa todas as migrations em ordem alfabética (0001_, 0002_, ...)
foreach (glob($root . '/migrations/*.sql') as $file) {
    echo "Executando migration: " . basename($file) . PHP_EOL;
    $sql = file_get_contents($file);
    if ($sql === false) {
        fwrite(STDERR, "Erro ao ler $file\n");
        exit(1);
    }
    $pdo->exec($sql);
}
echo "✅ Migrations aplicadas.\n";
