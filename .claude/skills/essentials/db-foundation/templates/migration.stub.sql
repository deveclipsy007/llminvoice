-- Migration: <NNN_change_name>
-- Contexto: descreva o objetivo em 1 linha

START TRANSACTION;

-- Exemplo de alteracao segura
-- ALTER TABLE `<table_name>`
--   ADD COLUMN `<column_name>` VARCHAR(255) NULL;

-- Exemplo de indice
-- CREATE INDEX `idx_<table>_<column>` ON `<table_name>` (`<column_name>`);

COMMIT;

-- Rollback sugerido (manter no PR/descricao operacional)
-- ALTER TABLE `<table_name>` DROP COLUMN `<column_name>`;
