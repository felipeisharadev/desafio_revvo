PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS cursos (
  id            INTEGER PRIMARY KEY AUTOINCREMENT,
  nome          TEXT    NOT NULL,
  descricao     TEXT,
  carga_horaria INTEGER NOT NULL DEFAULT 0,
  imagem        TEXT,                -- nome do arquivo salvo em /public/uploads/cursos
  link          TEXT,                -- URL do botão/CTA do card/slide
  criado_em     TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_cursos_nome ON cursos (nome);
