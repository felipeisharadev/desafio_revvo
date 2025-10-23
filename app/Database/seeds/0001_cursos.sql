BEGIN;

INSERT INTO cursos (nome, descricao, carga_horaria, imagem, link) VALUES
('PHP Básico',               'Introdução ao PHP e sintaxe fundamental.', 20, NULL, NULL),
('HTML & CSS',               'Estrutura e estilos para páginas responsivas.', 15, NULL, NULL),
('JavaScript Intermediário', 'DOM, eventos e lógica do front.',           30, NULL, NULL);

COMMIT;
