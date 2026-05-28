# ER - Modulo Pre-Matricula / Gestao de Vagas (Prefixo: pm_*)

Processo de inscricao e matricula de novos alunos na rede. 36 tabelas com integracao ao modulo Academico.

> Fonte: consulta direta ao banco PostgreSQL. Campos de auditoria (`criado_em`, `atualizado_em`, `removido_em`) omitidos por brevidade.

---

## 1. Processos e Configuracao (6 tabelas)

Configuracao dos processos seletivos, cursos e criterios de selecao.

```mermaid
erDiagram
    pm_processos {
        int id PK
        varchar str_nome
        varchar str_mensagem_manutencao
        int status_id FK
        int bool_manutencao
        int int_tipo_processo
        int periodo_letivo_academico_id FK
        bool bool_comparar_endereco
    }

    pm_cursos_academicos {
        int id PK
        varchar str_nome
        int curso_modulo_academico_id FK
    }

    pm_processos_cursos_academicos {
        int processo_id PK_FK
        int curso_academico_id PK_FK
        bool bool_cadastro_reserva
        bool bool_fila_unica_com_academico
        bool bool_consultar_cep
        bool bool_desempate_por_idade
        timestamp dt_inicio_inscricoes
        timestamp dt_termino_inscricoes
        int int_prazo_confirmacao_matricula
        bool bool_habilitar_educacao_especial
        bool bool_habilitar_inscricao_por_transferencia
        bool bool_enviar_email_inscricao
    }

    pm_anos_de_escolaridade {
        int id PK
        varchar str_nome
        int curso_academico_id FK
        int processo_id FK
        int ano_de_escolaridade_academico_id FK
        timestamp dt_inicio_inscricoes
        timestamp dt_termino_inscricoes
        timestamp dt_nascimento_minima
        timestamp dt_nascimento_maxima
        bool bool_cpf_obrigatorio
        bool bool_certidao_nascimento_obrigatoria
        bool bool_habilitar_alunos_egressos
        bool bool_desconsiderar_data_corte
    }

    pm_opcoes_de_unidades {
        int id PK
        int processo_id FK
        int curso_academico_id FK
        bool bool_unidade_obrigatoria
        bool bool_permitir_turno_irrelevante
        bool bool_habilitar_unidades_por_turma
        int int_ordem
    }

    pm_configuracoes {
        int id PK
        int int_quantidade_registros_por_pagina
        bool bool_exibir_tawkto
        int bool_habilitar_cadastro_responsavel
        int perfil_responsavel_id FK
    }

    pm_processos ||--o{ pm_processos_cursos_academicos : "cursos do processo"
    pm_cursos_academicos ||--o{ pm_processos_cursos_academicos : "processos do curso"
    pm_cursos_academicos ||--o{ pm_anos_de_escolaridade : "anos de escolaridade"
    pm_processos ||--o{ pm_anos_de_escolaridade : "anos por processo"
    pm_processos ||--o{ pm_opcoes_de_unidades : "opcoes de unidades"

    pm_processos }o--|| sa_periodos_letivos : "periodo_letivo - Academico"
    pm_cursos_academicos }o--|| sa_cursos : "curso - Academico"
```

---

## 2. Inscricoes e Candidatos (7 tabelas)

Inscricoes de candidatos, dependentes e responsaveis.

```mermaid
erDiagram
    pm_inscricoes {
        int id PK
        varchar str_nome_candidato
        varchar str_cpf_candidato
        varchar str_protocolo
        timestamp dt_nascimento_candidato
        int int_idade
        int processo_id FK
        int status_id FK
        int ano_de_escolaridade_id FK
        int turma_id FK
        int regiao_id FK
        int responsavel_id FK
        int dependente_id FK
        int pessoa_id FK
        int enturmacao_academico_id FK
        int bool_aluno_da_rede
        int bool_inscricao_por_transferencia
        bool bool_mandado_judicial
        bool bool_excepcionalidade
        bool bool_irmao_na_rede
        bool bool_necessidade_especial_especifica_com_prioridade
    }

    pm_dependentes {
        int id PK
        varchar str_nome
        date dt_de_nascimento
        varchar str_cpf
        text str_certidao_de_nascimento
        int int_modelo_certidao_de_nascimento
        bool bool_necessidade_especial
        int necessidade_especial_id FK
        varchar str_cartao_sus
        varchar str_nome_filiacao_1
        varchar str_cpf_filiacao_1
        int tipo_de_responsavel_filiacao_1_id FK
        varchar str_nome_filiacao_2
        int aluno_id FK
    }

    pm_responsaveis {
        int id PK
        int usuario_id FK
        int dependente_id FK
        int tipo_de_responsavel_id FK
    }

    pm_inscricoes_unidades {
        int id PK
        int int_prioridade
        int int_classificacao
        int inscricao_id FK
        int unidade_id FK
        int turno_id FK
        int tipo_de_turno_id FK
        int int_status
        int bool_encaminhamento
        int bool_nao_compareceu
        timestamp dt_situacao
    }

    pm_anotacoes {
        int id PK
        varchar str_descricao
        int inscricao_id FK
        int usuario_id FK
    }

    pm_comprovantes_inscricoes {
        int id PK
        varchar str_titulo
        varchar str_mensagem
        varchar str_nome_candidato
        varchar str_protocolo
        int inscricao_id FK
        int processo_id FK
        int status_id FK
    }

    pm_tipos_de_responsavel {
        int id PK
        varchar str_nome
        varchar str_sigla
        int tipo_de_responsavel_academico_id FK
    }

    pm_inscricoes ||--o{ pm_inscricoes_unidades : "opcoes de unidade"
    pm_inscricoes ||--o{ pm_anotacoes : "anotacoes"
    pm_inscricoes ||--o{ pm_comprovantes_inscricoes : "comprovantes"
    pm_dependentes ||--o{ pm_inscricoes : "inscricoes do dependente"
    pm_responsaveis }o--|| pm_dependentes : "responsavel do dependente"
    pm_tipos_de_responsavel ||--o{ pm_responsaveis : "tipo"

    pm_dependentes }o--o| sg_pessoas : "pessoa_id - Gerenciador"
```

---

## 3. Estrutura: Unidades, Turmas e Turnos (9 tabelas)

Espelho das unidades/turmas do modulo Academico para o contexto da pre-matricula.

```mermaid
erDiagram
    pm_unidades {
        int id PK
        varchar str_nome
        int unidade_academico_id FK
        int bairro_id FK
    }

    pm_turmas {
        int id PK
        varchar str_nome
        int turno_id FK
        int ano_de_escolaridade_id FK
        int unidade_id FK
        int int_qtd_total_vagas
        int int_qtd_vagas_disponiveis
        int turma_academico_id FK
        bool bool_disponivel_pre_matricula
        int int_qtd_max_alunos_nec_esp
    }

    pm_turnos {
        int id PK
        varchar str_nome
        int turno_academico_id FK
    }

    pm_regioes {
        int id PK
        varchar str_nome
        int processo_id FK
    }

    pm_bairros {
        int id PK
        varchar str_nome
    }

    pm_bairros_regioes {
        int regiao_id PK_FK
        int bairro_id PK_FK
    }

    pm_unidades_bairros {
        int processo_id PK_FK
        int unidade_id PK_FK
        int bairro_id PK_FK
    }

    pm_unidades_bairros_cursos {
        int processo_id PK_FK
        int unidade_id PK_FK
        int curso_id PK_FK
        int bairro_id PK_FK
    }

    pm_unidades_usuarios {
        int unidade_id PK_FK
        int usuario_id PK_FK
        int curso_academico_id PK_FK
    }

    pm_unidades ||--o{ pm_turmas : "turmas da unidade"
    pm_turnos ||--o{ pm_turmas : "turno"
    pm_anos_de_escolaridade ||--o{ pm_turmas : "ano"
    pm_regioes ||--o{ pm_bairros_regioes : "bairros"
    pm_bairros ||--o{ pm_bairros_regioes : "regioes"
    pm_unidades ||--o{ pm_unidades_bairros : "bairros atendidos"

    pm_unidades }o--|| sa_unidades : "unidade - Academico"
    pm_turmas }o--o| sa_turmas : "turma - Academico"
    pm_turnos }o--|| sa_turnos : "turno - Academico"
```

---

## 4. Enturmacao e Movimentacao (6 tabelas)

Resultado do processo: enturmacao, movimentacoes e ranqueamento.

```mermaid
erDiagram
    pm_enturmacoes {
        int id PK
        int aluno_id FK
        int processo_id FK
        int turma_id FK
        int inscricao_id FK
        int int_status
    }

    pm_movimentacoes {
        int id PK
        varchar str_justificativa
        int inscricao_id FK
        int unidade_id FK
        int turma_id FK
        int usuario_id FK
        int status_id FK
    }

    pm_movimentacoes_enturmacoes {
        int id PK
        varchar str_descricao
        int enturmacao_id FK
        int int_status
        int usuario_id FK
    }

    pm_ranqueamentos {
        int id PK
        int processo_id FK
        int ano_de_escolaridade_id FK
        int curso_academico_id FK
        int inscricao_id FK
        int unidade_id FK
        int turno_id FK
        int turma_academico_destino_id FK
        int int_pontuacao_total
        int int_classificacao
        int int_prioridade
        bool bool_excepcionalidade
        bool bool_convocado
    }

    pm_solicitacoes_de_classificacao {
        int id PK
        int usuario_id FK
        int processo_id FK
        bool bool_processado
        int int_situacao_do_processamento
        int curso_academico_id FK
    }

    pm_pedidos_transferencia {
        int id PK
        varchar str_justificativa
        int aluno_id FK
        int processo_id FK
        int unidade_id FK
        int bairro_id FK
        int turno_id FK
        int enturmacao_origem_id FK
        int enturmacao_destino_id FK
        int int_status
    }

    pm_inscricoes ||--o{ pm_enturmacoes : "gera enturmacao"
    pm_turmas ||--o{ pm_enturmacoes : "turma"
    pm_inscricoes ||--o{ pm_movimentacoes : "movimentacoes"
    pm_inscricoes ||--o{ pm_ranqueamentos : "ranqueamento"
    pm_enturmacoes ||--o{ pm_movimentacoes_enturmacoes : "movimentacoes"
    pm_processos ||--o{ pm_solicitacoes_de_classificacao : "solicitacoes"

    pm_movimentacoes }o--|| sg_usuarios : "usuario_id - Gerenciador"
```

---

## 5. Alunos e Importacao (5 tabelas)

Alunos da pre-matricula, alunos importados e autorizados.

```mermaid
erDiagram
    pm_alunos {
        int id PK
        varchar str_matricula
        int pessoa_id FK
        int responsavel_id FK
    }

    pm_alunos_importados {
        int id PK
        varchar str_nome
        varchar str_filiacao1
        varchar str_filiacao2
        timestamp dt_nascimento
        int unidade_id FK
        int processo_id FK
        int int_status
        int aluno_id FK
    }

    pm_alunos_autorizados {
        int id PK
        varchar str_nome
        varchar str_filiacao1
        varchar str_filiacao2
        timestamp dt_nascimento
        int unidade_id FK
        int ano_de_escolaridade_id FK
        int int_status
    }

    pm_anexos {
        int id PK
        varchar str_descricao
        varchar str_nome_arquivo
        varchar str_path_arquivo
        int processo_id FK
        int curso_academico_id FK
    }

    pm_status {
        int id UK
        varchar str_nome
        varchar str_cor
    }

    pm_alunos }o--|| sg_pessoas : "pessoa_id - Gerenciador"
    pm_alunos_importados }o--|| pm_processos : "processo"
```

---

## 6. Comunicacao e Auxiliares (3 tabelas)

SMS, anotacoes de alunos e necessidades especiais por processo.

```mermaid
erDiagram
    pm_mensagens_sms {
        int id PK
        varchar str_telefone
        varchar str_remetente
        varchar str_mensagem
        int usuario_id FK
        int modulo_id FK
        int processo_id FK
    }

    pm_anotacoes_alunos {
        int id PK
        varchar str_descricao
        int enturmacao_id FK
        int processo_id FK
        int usuario_id FK
    }

    pm_necessidades_especiais_processos {
        int necessidade_especial_id PK_FK
        int processo_id PK_FK
    }

    pm_processos ||--o{ pm_mensagens_sms : "mensagens"
    pm_processos ||--o{ pm_necessidades_especiais_processos : "necessidades"
```

---

## Resumo

| # | Grupo | Tabelas | Descricao |
|---|---|---|---|
| 1 | Processos e Configuracao | 6 | Processos seletivos, criterios de selecao |
| 2 | Inscricoes e Candidatos | 7 | Inscricoes, dependentes, responsaveis |
| 3 | Estrutura | 9 | Unidades, turmas, turnos, regioes (espelho Academico) |
| 4 | Enturmacao e Movimentacao | 6 | Resultados, ranqueamento, transferencias |
| 5 | Alunos e Importacao | 5 | Alunos, importacao, status |
| 6 | Comunicacao e Auxiliares | 3 | SMS, anotacoes, necessidades especiais |
| | **TOTAL** | **36** | |

## Integracao com Modulo Academico

O modulo Pre-Matricula espelha diversas entidades do modulo Academico:

| Pre-Matricula | Academico | FK |
|---|---|---|
| `pm_processos` | `sa_periodos_letivos` | `periodo_letivo_academico_id` |
| `pm_cursos_academicos` | `sa_cursos` | `curso_modulo_academico_id` |
| `pm_unidades` | `sa_unidades` | `unidade_academico_id` |
| `pm_turmas` | `sa_turmas` | `turma_academico_id` |
| `pm_turnos` | `sa_turnos` | `turno_academico_id` |
| `pm_anos_de_escolaridade` | `sa_anos_de_escolaridade` | `ano_de_escolaridade_academico_id` |
