# 03 - Diagramas de Entidade-Relacionamento (ER)

Os diagramas ER foram separados em arquivos individuais por modulo para melhor legibilidade.

Acesse a pasta: [entidade-relacionamento/](entidade-relacionamento/)

| # | Arquivo | Modulo | Prefixo BD |
|---|---------|--------|------------|
| 01 | [Gerenciador](entidade-relacionamento/01-gerenciador.md) | Core: usuarios, pessoas, permissoes | `sg_*` |
| 02 | [Academico](entidade-relacionamento/02-academico.md) | Alunos, turmas, notas, frequencia, grupos de aulas, faltas diarias | `sa_*` |
| 03 | [Pre-Matricula](entidade-relacionamento/03-pre-matricula.md) | Inscricoes, processos, vagas | `pm_*` |
| 04 | [Cross-Module](entidade-relacionamento/04-cross-module.md) | Foreign keys entre modulos | `sg_*`, `sa_*`, `pm_*` |
| 05 | [Busca Ativa](entidade-relacionamento/05-busca-ativa.md) | Alertas, casos, visitas tecnicas, formularios | `ba_*` |
| 06 | [Caixa Escolar](entidade-relacionamento/06-caixa-escolar.md) | Gestao financeira escolar | `ce_*` |
| 07 | [Portal de Cursos](entidade-relacionamento/07-portal-de-cursos.md) | Cursos presenciais + online, trilhas, questionarios, credenciamento, cracha | `pc_*` |
| 08 | [Transporte](entidade-relacionamento/08-transporte.md) | Veiculos, rotas, motoristas, monitores, agendamentos | `tr_*` |
