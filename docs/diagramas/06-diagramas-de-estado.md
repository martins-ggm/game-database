# 06 - Diagramas de Estado

Diagramas de estado UML (Mermaid `stateDiagram-v2`) que modelam o ciclo de vida das entidades do SISP.

## Indice

| # | Entidade | Modulo | Estados | Arquivo |
|---|---|---|---|---|
| 01 | Inscricao | Processo Seletivo | 11 | [diagrama-de-estado/01-inscricao-processo-seletivo.md](diagrama-de-estado/01-inscricao-processo-seletivo.md) |

## Como Ler os Diagramas

- **Estado inicial** `[*] -->`: ponto de entrada (criacao da entidade)
- **Estado final** `--> [*]`: estado terminal (sem transicoes de saida)
- **Transicoes**: setas rotuladas com a acao que dispara a mudanca
- **Guardas** `[condicao]`: pre-condicao necessaria para a transicao
- **Estados compostos**: agrupam sub-estados dentro de uma fase
