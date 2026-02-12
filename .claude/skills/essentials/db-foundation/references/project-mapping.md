# Mapeamento do projeto

- Schema principal: `database/schema.sql`
- Migrations incrementais: `database/migrations/*.sql`
- Runner de migrations: `database/run_migrations.php`

## Regras praticas observadas

- Prefixo numerico em migrations (`001_`, `008_`, etc).
- Uso de InnoDB + utf8mb4.
- Forte uso de FK entre `clients`, `users`, `proposals` e tabelas de suporte.
