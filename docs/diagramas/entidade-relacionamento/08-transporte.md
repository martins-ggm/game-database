# ER - Modulo Transporte (Prefixo: tr_*)

2 tabelas documentadas (tr_veiculos, tr_rotas). Modulo de gestao de transporte escolar: veiculos, rotas, motoristas, monitores, alunos, frequencias, vistorias, afericoes e agendamentos.

> **Fluxo principal:** Veiculos (`tr_veiculos`) sao vinculados a Fornecedores (`tr_fornecedores`) e possuem Tipo de Veiculo (`tr_tipos_de_veiculo`) e Tipo de Combustivel (`tr_tipos_de_combustivel`). Veiculos percorrem Rotas (`tr_rotas`) via `tr_rotas_veiculos`. Passam por Vistorias (`tr_vistorias`), Afericoes (`tr_afericoes`) e podem ser Agendados (`tr_agendamentos_de_veiculos`).

## 1. Veiculo

```mermaid
erDiagram
    tr_veiculos {
        int id PK
        text str_renavam
        text str_modelo
        text str_marca
        text str_placa
        text str_cadastro_detran
        text str_inspecao
        text str_seguro
        text str_licenciamento
        int int_ano_de_fabricacao
        int int_capacidade
        datetime dt_validade_seguro
        date dt_inatividade
        boolean bool_adaptado_para_ne
        boolean bool_ativo "default true"
        boolean bool_uso_interno "default false"
        boolean bool_possui_cronotacografo "default false"
        boolean bool_veiculo_proprio "NOVO"
        int fornecedor_id FK
        int tipo_de_veiculo_id FK
        int tipo_de_combustivel_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_fornecedores {
        int id PK
        text str_nome
        boolean bool_ativo
        boolean bool_oferece_transporte
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_tipos_de_veiculo {
        int id PK
        text str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_tipos_de_combustivel {
        int id PK
        text str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_fornecedores ||--o{ tr_veiculos : "fornece"
    tr_tipos_de_veiculo ||--o{ tr_veiculos : "classifica"
    tr_tipos_de_combustivel ||--o{ tr_veiculos : "abastece com"
```

## 2. Rota

```mermaid
erDiagram
    tr_rotas {
        int id PK
        text str_nome
        bigint int_custo_diario
        bigint int_valor_previsto_em_contrato
        float float_quilometragem
        int int_zona
        text str_sigla
        text str_numero_do_processo
        text str_numero_do_contrato
        text hr_partida_ida
        text hr_partida_volta
        int int_total_de_vagas
        int int_total_de_vagas_disponiveis
        int int_total_de_vagas_cedidas
        int int_total_de_vagas_contratadas
        boolean bool_ativo
        int int_responsavel "NOVO — 1=municipio 2=fornecedor"
        int fornecedor_id FK
        int periodo_letivo_id FK
        int tipo_de_rota_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_fornecedores ||--o{ tr_rotas : "opera"
    sa_periodos_letivos ||--o{ tr_rotas : "vigencia"
    tr_tipos_de_rota ||--o{ tr_rotas : "classifica"

    sa_periodos_letivos {
        int id PK
        text str_nome
    }

    tr_tipos_de_rota {
        int id PK
        text str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }
```

## 3. Relacionamentos da Rota

```mermaid
erDiagram
    tr_rotas ||--o{ tr_rotas_veiculos : "utiliza"
    tr_rotas ||--o{ tr_rotas_motoristas : "conduzida por"
    tr_rotas ||--o{ tr_rotas_monitores : "monitorada por"
    tr_rotas ||--o{ tr_rotas_turnos : "opera em"
    tr_rotas ||--o{ tr_rotas_unidades : "atende"
    tr_rotas ||--o{ tr_alunos : "transporta"
    tr_rotas ||--o{ tr_pontos_de_parada : "para em"
    tr_rotas ||--o{ tr_itinerarios : "segue"

    tr_rotas_veiculos {
        int rota_id FK
        int veiculo_id FK
        datetime dt_entrada
        datetime dt_saida
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_rotas_motoristas {
        int rota_id FK
        int motorista_id FK
        datetime dt_entrada
        datetime dt_saida
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_rotas_monitores {
        int rota_id FK
        int monitor_id FK
        datetime dt_entrada
        datetime dt_saida
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_rotas_turnos {
        int turno_id FK
        int rota_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_rotas_unidades {
        int rota_id FK
        int unidade_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }
```

## 4. Relacionamentos do Veiculo

```mermaid
erDiagram
    tr_veiculos ||--o{ tr_rotas_veiculos : "percorre"
    tr_veiculos ||--o{ tr_vistorias : "passa por"
    tr_veiculos ||--o{ tr_afericoes : "tem afericoes"
    tr_veiculos ||--o{ tr_agendamentos_de_veiculos : "e agendado"

    tr_rotas_veiculos {
        int veiculo_id FK
        int rota_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_vistorias {
        int id PK
        int veiculo_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_afericoes {
        int id PK
        int veiculo_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    tr_agendamentos_de_veiculos {
        int id PK
        int veiculo_id FK
        text str_descricao
        text str_motivo_do_cancelamento
        int int_situacao
        datetime dt_prevista_de_retirada
        datetime dt_prevista_de_devolucao
        datetime dt_efetiva_de_retirada
        datetime dt_efetiva_de_devolucao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }
```

## Novos Campos (SPRINT-163)

| Tabela | Campo | Tipo | Valores | Descricao |
|--------|-------|------|---------|-----------|
| `tr_veiculos` | `bool_veiculo_proprio` | `boolean` | true/false | Indica se o veiculo e de propriedade da secretaria/municipio (`true`) ou pertence a terceiro/fornecedor (`false`) |
| `tr_rotas` | `int_responsavel` | `integer` | 1=municipio, 2=fornecedor | Indica quem e o responsavel pela operacao da rota |

> **Nota:** Campos adicionados via migration de atualizacao (SPRINT-163). Sem UNIQUE ou CHECK no banco — validacao de negocio no Service/DTO conforme padrao do projeto. O campo `int_responsavel` utiliza enum `ResponsavelDaRotaEnum`.

## Dependencias Externas (Cross-Module)

| FK em tr_veiculos | Tabela Externa | Modulo |
|---|---|---|
| `tr_veiculos.fornecedor_id` | `tr_fornecedores` | Transporte (interno) |
| `tr_veiculos.tipo_de_veiculo_id` | `tr_tipos_de_veiculo` | Transporte (interno) |
| `tr_veiculos.tipo_de_combustivel_id` | `tr_tipos_de_combustivel` | Transporte (interno) |
