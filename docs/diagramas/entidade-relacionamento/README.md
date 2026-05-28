# Diagramas de Entidade-Relacionamento (ER)

Um arquivo por modulo, com tabelas, campos e relacionamentos reais do banco PostgreSQL.

| # | Arquivo | Modulo | Prefixo BD |
|---|---------|--------|------------|
| 01 | [01-gerenciador.md](01-gerenciador.md) | Gerenciador (Core) - 52 tabelas | `sg_*` |
| 02 | [02-academico.md](02-academico.md) | Academico - 294 tabelas (258 + 33 censo + 3 portal professor) | `sa_*` |
| 03 | [03-pre-matricula.md](03-pre-matricula.md) | Pre-Matricula / Gestao de Vagas - 36 tabelas | `pm_*` |
| 04 | [04-cross-module.md](04-cross-module.md) | Relacionamentos entre modulos (24 FKs) | `sg_*`, `sa_*`, `pm_*` |
| 05 | [05-busca-ativa.md](05-busca-ativa.md) | Busca Ativa - 23 tabelas (20 + 3 pivos) | `ba_*` |
| 06 | [06-caixa-escolar.md](06-caixa-escolar.md) | Caixa Escolar - 53 tabelas | `ce_*` |
| 07 | [07-portal-de-cursos.md](07-portal-de-cursos.md) | Portal de Cursos - 52 tabelas (34 presenciais + 15 online + 3 credenciamento) | `pc_*` |
| 08 | [08-transporte.md](08-transporte.md) | Transporte - tr_veiculos (foco: bool_veiculo_proprio) | `tr_*` |
| 09 | [09-gestao-de-conselho.md](09-gestao-de-conselho.md) | Gestao de Conselho - em construcao (6 cadastros documentados) | `gc_*` |
