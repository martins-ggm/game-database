# Diagramas de Documentacao - SISP

Documentacao visual do Sistema Integrado de Gestao Escolar (SISP) em formato Mermaid.

> Para visualizar os diagramas, use a extensao **Mermaid Preview** no VS Code ou visualize diretamente no GitHub/GitLab.

## Indice de Diagramas

| # | Diagrama | Descricao | Arquivo |
|---|----------|-----------|---------|
| 01 | **Arquitetura Geral** | Visao macro do sistema: atores, containers e servicos externos | [01-arquitetura-geral.md](01-arquitetura-geral.md) |
| 02 | **Camadas Arquiteturais** | Padrao de camadas: Routes - Controllers - Services - Repositories - Models | [02-camadas-arquiteturais.md](02-camadas-arquiteturais.md) |
| 03 | **Entidade-Relacionamento** | Modelo de dados por modulo (1 arquivo por modulo) | [entidade-relacionamento/](entidade-relacionamento/) |
| 04 | **Fluxos de Sequencia** | Fluxos criticos: Autenticacao, Matricula e Lancamento de Notas | [04-fluxos-de-sequencia.md](04-fluxos-de-sequencia.md) |
| 05 | **Modulos e Dependencias** | Mapa dos 24+ modulos e suas inter-dependencias | [05-modulos-e-dependencias.md](05-modulos-e-dependencias.md) |
| 06 | **Diagramas de Estado** | Ciclo de vida de entidades (estados e transicoes) | [diagrama-de-estado/](diagrama-de-estado/) |
| 07 | **Casos de Uso** | Casos de uso por modulo (1 arquivo por modulo) | [casos-de-uso/](casos-de-uso/) |
| -- | **Portal de Cursos - Melhorias** | Modelagem das novas funcionalidades: credenciamento, cracha e controle de acesso | [portal-cursos-melhorias.md](portal-cursos-melhorias.md) |

## Tecnologias do SISP

- **Backend:** Laravel 10 (PHP 8.2)
- **Frontend:** Vue 3 + Tailwind CSS
- **Banco de Dados:** PostgreSQL
- **Autenticacao:** Laravel Passport (OAuth2) + Session
- **Infraestrutura:** Google Cloud App Engine + AWS S3
- **Arquitetura:** MVC Modular com Service/Repository Pattern
