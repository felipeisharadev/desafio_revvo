PRAGMA foreign_keys = ON;
PRAGMA journal_mode = WAL;
PRAGMA synchronous = NORMAL;

BEGIN;

CREATE TABLE IF NOT EXISTS cursos (
  id             INTEGER PRIMARY KEY AUTOINCREMENT,
  nome           TEXT    NOT NULL CHECK (length(trim(nome)) > 0),
  descricao      TEXT,
  carga_horaria  INTEGER NOT NULL CHECK (carga_horaria > 0),
  imagem         TEXT    CHECK (imagem IS NULL OR length(imagem) <= 255),
  link           TEXT    CHECK (link   IS NULL OR length(link)   <= 2048),
  criado_em      TEXT    NOT NULL DEFAULT (strftime('%Y-%m-%d %H:%M:%f','now')),
  atualizado_em  TEXT    NOT NULL DEFAULT (strftime('%Y-%m-%d %H:%M:%f','now'))
) STRICT;

CREATE INDEX IF NOT EXISTS idx_cursos_nome ON cursos (nome);

CREATE TRIGGER IF NOT EXISTS trg_cursos_updated
AFTER UPDATE ON cursos
FOR EACH ROW
BEGIN
  UPDATE cursos
     SET atualizado_em = strftime('%Y-%m-%d %H:%M:%f','now')
   WHERE id = NEW.id;
END;

COMMIT;
