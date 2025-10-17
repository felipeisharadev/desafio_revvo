<?php
declare(strict_types=1);

// Caminho do arquivo do banco
$path = dirname(__DIR__) . '/database.sqlite';

if (file_exists($path)) {
    echo "database.sqlite já existe em: {$path}\n";
    exit(0);
}

// Garante que diretórios de upload existam
@mkdir(dirname(__DIR__) . '/public/uploads', 0777, true);
@mkdir(dirname(__DIR__) . '/public/uploads/cursos', 0777, true);

$pdo = new PDO('sqlite:' . $path);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("
CREATE TABLE cursos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nome TEXT NOT NULL,
  descricao TEXT,
  carga_horaria INTEGER NOT NULL DEFAULT 0,
  imagem TEXT,           
  link TEXT,              
  criado_em TEXT DEFAULT CURRENT_TIMESTAMP
);
");

// Seeds de exemplo
$pdo->exec("
INSERT INTO cursos (nome, descricao, carga_horaria, imagem, link) VALUES
('PHP Básico', 'Introdução ao PHP e sintaxe fundamental.', 20, NULL, '#'),
('HTML & CSS', 'Estrutura e estilos para páginas responsivas.', 15, NULL, '#'),
('JavaScript Intermediário', 'DOM, eventos e lógica do front.', 30, NULL, '#');
");

echo "OK: database.sqlite criado em {$path}\n";
