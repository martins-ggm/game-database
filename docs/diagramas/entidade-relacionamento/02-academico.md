# ER - Modulo Academico (Prefixo: sa_*)

Coracao do sistema educacional: 259 tabelas (+ 33 de censo + 3 portal do professor + 6 legado SISLAME) organizadas em 20 grupos logicos.

> Fonte: consulta direta ao banco PostgreSQL. Campos de auditoria (`criado_em`, `atualizado_em`, `removido_em`) omitidos por brevidade.

---

## 1. Alunos e Responsaveis (14 tabelas)

Cadastro de alunos, responsaveis, deficiencias e documentacao.

```mermaid
erDiagram
    sa_alunos {
        int id PK
        varchar str_matricula
        varchar str_id_aluno_inep
        int int_situacao
        timestamp dt_matricula_na_rede
        int pessoa_id FK
        bool bool_possui_deficiencia
        bool bool_auxilio_brasil
        varchar str_url_foto
    }

    sa_responsaveis {
        int id PK
        int pessoa_id FK
    }

    sa_alunos_responsaveis {
        int aluno_id PK_FK
        int responsavel_id PK_FK
        int tipo_de_responsavel_id FK
        bool bool_responsavel_legal
        bool bool_responsavel_academico
        bool bool_filiacao
    }

    sa_alunos_responsaveis_historico {
        int id PK
        int aluno_id FK
        int responsavel_id FK
        int usuario_insercao_id FK
        int usuario_remocao_id FK
        varchar str_tipo_acao
    }

    sa_responsaveis_por_alunos {
        int id PK
        int aluno_id FK
        int responsavel_id FK
        bool bool_responsavel_legal
        bool bool_responsavel_academico
        bool bool_falecido
    }

    sa_responsaveis_por_saida_dos_alunos {
        int id PK
        varchar str_nome
        int int_status
        int enturmacao_id FK
        int usuario_autorizacao_id FK
    }

    sa_tipos_de_responsavel {
        int id PK
        varchar str_nome
        bool bool_filiacao
    }

    sa_deficiencias_alunos {
        int id PK
        int cid_id FK
        int aluno_id FK
        bool bool_possui_laudo_medico
    }

    sa_laudos_deficiencias {
        int id PK
        int deficiencia_aluno_id FK
        text str_path
        text str_url
    }

    sa_irmaos {
        int id PK
        int aluno_um_id FK
        int aluno_dois_id FK
    }

    sa_carteiras_de_estudante {
        int id PK
        int aluno_id FK
        int enturmacao_id FK
        int layout_carteirinha_id FK
        date dt_validade
    }

    sa_fichas_de_matricula {
        int id PK
        int aluno_id FK
        int unidade_id FK
        timestamp dt_matricula_na_unidade
    }

    sa_status_alunos {
        int id
        varchar str_nome
        varchar str_cor
    }

    sa_historico_de_nomes_civis {
        int id PK
        text str_nome_novo
        text str_nome_antigo
        int pessoa_id FK
        int usuario_alteracao_id FK
    }

    sa_alunos ||--o{ sa_alunos_responsaveis : "tem responsaveis"
    sa_responsaveis ||--o{ sa_alunos_responsaveis : "responsavel de"
    sa_tipos_de_responsavel ||--o{ sa_alunos_responsaveis : "tipo"
    sa_alunos ||--o{ sa_deficiencias_alunos : "deficiencias"
    sa_deficiencias_alunos ||--o{ sa_laudos_deficiencias : "laudos"
    sa_alunos ||--o{ sa_carteiras_de_estudante : "carteiras"
    sa_alunos ||--o{ sa_fichas_de_matricula : "fichas matricula"
    sa_alunos ||--o{ sa_irmaos : "irmaos"
    sa_alunos ||--o{ sa_responsaveis_por_alunos : "responsaveis"

    sa_alunos }o--|| sg_pessoas : "pessoa_id - Gerenciador"
    sa_responsaveis }o--|| sg_pessoas : "pessoa_id - Gerenciador"
```

---

## 2. Unidades e Estrutura (18 tabelas)

Escolas, cursos, periodos letivos, turnos e regioes.

```mermaid
erDiagram
    sa_unidades {
        int id PK
        varchar str_nome
        varchar str_codigo_inep
        varchar str_cnpj
        varchar str_email
        varchar str_telefone
        int int_zona_unidade
        bool bool_status
        int diretor_id FK
        int endereco_id FK
        int secretario_id FK
        int tipo_de_unidade_id FK
        int regiao_id FK
        bool bool_mantenedora
    }

    sa_cursos {
        int id PK
        varchar str_nome
        varchar str_abreviatura
        int int_estrutura_curricular
        bool bool_curso_aee
        int curso_sucessor_id FK
    }

    sa_periodos_letivos {
        int id PK
        varchar str_nome
        varchar str_ano
        bool bool_padrao
        timestamp dt_inicial
        timestamp dt_final
        int secretario_de_educacao_id FK
    }

    sa_turnos {
        int id PK
        varchar str_nome
        bool bool_integral
        int tipo_de_turno_id FK
    }

    sa_salas {
        int id PK
        varchar str_nome
        int periodo_letivo_id FK
        int unidade_id FK
        int int_capacidade
        bool bool_ativo
    }

    sa_cursos_unidades {
        int id PK
        int curso_id FK
        int unidade_id FK
        int periodo_letivo_id FK
    }

    sa_periodos_letivos_cursos {
        int periodo_letivo_id PK_FK
        int curso_id PK_FK
        int int_percentual_nota_aprovacao
        int int_percentual_frequencia_aprovacao
        bool bool_permitir_matricula
        bool bool_permitir_lancamentos
    }

    sa_regioes {
        int id PK
        varchar str_nome
        int periodo_letivo_id FK
        int curso_id FK
    }

    sa_bairros {
        int id PK
        varchar str_nome
        int pais_id FK
        int estado_id FK
        int cidade_id FK
    }

    sa_tipos_de_unidades {
        int id PK
        varchar str_nome
    }

    sa_tipos_de_turnos {
        int id PK
        varchar str_nome
    }

    sa_cursos ||--o{ sa_cursos_unidades : "curso em unidades"
    sa_unidades ||--o{ sa_cursos_unidades : "unidade com cursos"
    sa_periodos_letivos ||--o{ sa_cursos_unidades : "periodo"
    sa_periodos_letivos ||--o{ sa_periodos_letivos_cursos : "config por curso"
    sa_cursos ||--o{ sa_periodos_letivos_cursos : "config por periodo"
    sa_unidades ||--o{ sa_salas : "salas"
    sa_regioes ||--o{ sa_regioes_bairros : "bairros da regiao"
    sa_regioes ||--o{ sa_regioes_unidades : "unidades da regiao"
    sa_tipos_de_unidades ||--o{ sa_unidades : "tipo"
    sa_tipos_de_turnos ||--o{ sa_turnos : "tipo"

    sa_unidades }o--|| sg_usuarios : "diretor_id - Gerenciador"
```

Tabelas pivot adicionais: `sa_regioes_bairros`, `sa_regioes_unidades`, `sa_unidades_bairros`, `sa_unidades_bairros_cursos_periodos_letivos`, `sa_unidades_usuarios`, `sa_unidades_usuarios_ambiente_online`, `sa_unidades_historico`, `sa_cursos_modelos_de_documentos`, `sa_cursos_tipos_de_documento`.

---

## 3. Turmas e Enturmacoes (12 tabelas)

Turmas, anos de escolaridade, enturmacao de alunos e vinculos profissionais.

```mermaid
erDiagram
    sa_turmas {
        int id PK
        varchar str_nome
        bool bool_ativo
        int int_total_vagas
        int unidade_id FK
        int curso_id FK
        int ano_de_escolaridade_id FK
        int turno_id FK
        int periodo_letivo_id FK
        int grade_curricular_id FK
        int horario_id FK
        int sala_id FK
        bool bool_multiseriada
        bool bool_extensao
    }

    sa_anos_de_escolaridade {
        int id PK
        varchar str_nome
        int int_numero_do_ano_de_escolaridade
        int curso_id FK
        int int_idade_de_referencia
    }

    sa_enturmacoes {
        int id PK
        int aluno_id FK
        int curso_id FK
        int periodo_letivo_id FK
        int turma_id FK
        int unidade_id FK
        int ano_de_escolaridade_id FK
        int int_situacao
        int int_numero_de_classe
        timestamp dt_matricula
        bool bool_enturmacao_integral
        int usuario_remocao_id FK
        int monitor_id FK
    }

    sa_status_enturmacoes {
        int id
        varchar str_nome
        varchar str_cor
    }

    sa_turmas_disciplinas {
        int id PK
        int turma_id FK
        int disciplina_id FK
        int professor_id FK
    }

    sa_turmas_anos_de_escolaridade {
        int id PK
        int turma_id FK
        int ano_de_escolaridade_id FK
    }

    sa_enturmacoes_tipos_de_documento {
        int enturmacao_id PK_FK
        int tipo_de_documento_id PK_FK
    }

    sa_turmas ||--o{ sa_enturmacoes : "alunos enturmados"
    sa_anos_de_escolaridade ||--o{ sa_turmas : "ano da turma"
    sa_turmas ||--o{ sa_turmas_disciplinas : "disciplinas"
    sa_turmas ||--o{ sa_turmas_anos_de_escolaridade : "anos multiseriada"
    sa_enturmacoes ||--o{ sa_enturmacoes_tipos_de_documento : "documentos"

    sa_enturmacoes }o--|| sa_alunos : "aluno_id"
    sa_enturmacoes }o--|| sa_turmas : "turma_id"
    sa_enturmacoes }o--|| sa_unidades : "unidade_id"
    sa_enturmacoes }o--|| sa_cursos : "curso_id"
    sa_enturmacoes }o--|| sa_periodos_letivos : "periodo_letivo_id"
    sa_enturmacoes }o--o| sg_usuarios : "usuario_remocao_id - Gerenciador"
```

Tabelas pivot profissionais por turma: `sa_turmas_coordenadores_de_ensino`, `sa_turmas_inspetores_escolares`, `sa_turmas_monitores`, `sa_turmas_pedagogos`, `sa_turmas_professores_especialistas`.

---

## 4. Disciplinas e Grades Curriculares (13 tabelas)

Disciplinas, areas de conhecimento, grades curriculares e etapas.

```mermaid
erDiagram
    sa_disciplinas {
        int id PK
        varchar str_nome
        varchar str_abreviatura
        int area_de_conhecimento_id FK
        smallint int_codigo_inep
    }

    sa_areas_de_conhecimento {
        int id PK
        varchar str_nome
        int area_principal_id FK
    }

    sa_grades_curriculares {
        int id PK
        varchar str_nome
        int int_status
        int curso_id FK
        int periodo_letivo_id FK
    }

    sa_grades_curriculares_disciplinas {
        int grade_curricular_id PK_FK
        int disciplina_id PK_FK
        int ano_de_escolaridade_id PK_FK
        bool bool_apura_nota
        bool bool_apura_falta
        int int_carga_horaria
        int arredondamento_id FK
        bool bool_avaliar_em_conceito
    }

    sa_grades_curriculares_eletivas {
        int grade_curricular_id FK
        int disciplina_id FK
        int ano_de_escolaridade_id FK
        int etapa_id FK
        bool bool_apura_frequencia
        int int_carga_horaria
        int int_minutos_equivalente_aula
        int base_de_conhecimento_id FK
        int disciplina_referencia_id FK
        bool bool_considerar_nota_para_resultado_final
        bool bool_considerar_falta_para_resultado_final
    }

    sa_etapas {
        int id PK
        varchar str_nome
        varchar str_abreviatura
        int int_valor
        timestamp dt_inicial
        timestamp dt_final
        int curso_id FK
        int periodo_letivo_id FK
        int int_tipo_da_etapa
        bool bool_possui_recuperacao
        int tipo_de_etapa_id FK
    }

    sa_tipos_de_etapas {
        int id PK
        varchar str_nome
        bool bool_resultado_final
        bool bool_recuperacao_final
    }

    sa_etapas_disciplinas {
        int id PK
        int etapa_id FK
        int disciplina_id FK
        float float_nota_maxima_bnc
    }

    sa_etapas_unidades {
        int id PK
        int etapa_id FK
        int unidade_id FK
        timestamp dt_inicial
        timestamp dt_final
    }

    sa_areas_de_conhecimento ||--o{ sa_disciplinas : "area"
    sa_grades_curriculares ||--o{ sa_grades_curriculares_disciplinas : "disciplinas"
    sa_disciplinas ||--o{ sa_grades_curriculares_disciplinas : "em grades"
    sa_grades_curriculares ||--o{ sa_grades_curriculares_eletivas : "eletivas"
    sa_etapas ||--o{ sa_etapas_disciplinas : "disciplinas da etapa"
    sa_etapas ||--o{ sa_etapas_unidades : "datas por unidade"
    sa_tipos_de_etapas ||--o{ sa_etapas : "tipo"
    sa_cursos ||--o{ sa_grades_curriculares : "grade do curso"
    sa_cursos ||--o{ sa_etapas : "etapas do curso"
```

Tabelas auxiliares: `sa_disciplinas_referenciais`, `sa_etapas_por_tela`, `sa_observacoes_das_disciplinas_eletivas`, `sa_observacoes_das_turmas`.

---

## 5. Notas e Avaliacoes (20 tabelas)

Lancamento de notas, avaliacoes, fichas de disciplina e arredondamentos.

```mermaid
erDiagram
    sa_avaliacoes {
        int id PK
        varchar str_nome
        float float_valor
        int int_tipo_avaliacao
        timestamp dt_avaliacao
        bool bool_recuperacao
        int disciplina_id FK
        int turma_id FK
        int etapa_id FK
        int curso_id FK
        int grupo_de_avaliacao_id FK
    }

    sa_fichas_de_disciplinas {
        int id PK
        int enturmacao_id FK
        int disciplina_id FK
        int turma_id FK
        int int_total_faltas
        float float_total_final
        int int_situacao
        varchar str_conceito
    }

    sa_fichas_de_disciplinas_etapas {
        int id PK
        int ficha_de_disciplina_id FK
        int etapa_id FK
        float float_nota
        int int_total_faltas
        varchar str_conceito
    }

    sa_notas_avaliacoes {
        int id PK
        float float_nota
        int avaliacao_id FK
        int ficha_de_disciplina_id FK
        int etapa_id FK
        varchar str_conceito
    }

    sa_grupos_de_avaliacoes {
        int id PK
        varchar str_nome
        int etapa_id FK
        int curso_id FK
        int periodo_letivo_id FK
        int int_tipo_avaliacao
        float float_valor
    }

    sa_avaliacoes_descritivas {
        int id PK
        varchar str_nome
        varchar str_instrucoes
    }

    sa_arredondamentos {
        int id PK
        varchar str_nome
    }

    sa_faixas_arredondamento {
        int id PK
        float float_faixa_inicial
        float float_faixa_final
        float float_nota
        int arredondamento_id FK
    }

    sa_equivalencias_de_notas_e_conceitos {
        int id PK
        varchar str_nome
        int curso_id FK
    }

    sa_itens_da_equivalencia_de_notas_e_conceitos {
        int id PK
        int int_tipo
        float float_percentual_inicial
        float float_percentual_final
        varchar str_conceito
        int equivalencia_de_notas_e_conceito_id FK
    }

    sa_autorizacoes_de_lancamento {
        int id PK
        int usuario_id FK
        int turma_id FK
        int disciplina_id FK
        int etapa_id FK
        timestamp dt_limite
    }

    sa_etapas ||--o{ sa_avaliacoes : "avaliacoes da etapa"
    sa_disciplinas ||--o{ sa_avaliacoes : "avaliacoes da disciplina"
    sa_turmas ||--o{ sa_avaliacoes : "avaliacoes da turma"
    sa_enturmacoes ||--o{ sa_fichas_de_disciplinas : "fichas do aluno"
    sa_disciplinas ||--o{ sa_fichas_de_disciplinas : "ficha da disciplina"
    sa_fichas_de_disciplinas ||--o{ sa_fichas_de_disciplinas_etapas : "notas por etapa"
    sa_fichas_de_disciplinas ||--o{ sa_notas_avaliacoes : "notas"
    sa_avaliacoes ||--o{ sa_notas_avaliacoes : "notas da avaliacao"
    sa_arredondamentos ||--o{ sa_faixas_arredondamento : "faixas"
    sa_equivalencias_de_notas_e_conceitos ||--o{ sa_itens_da_equivalencia_de_notas_e_conceitos : "itens"
    sa_grupos_de_avaliacoes ||--o{ sa_avaliacoes : "grupo"
```

Tabelas complementares: `sa_avaliacoes_descritivas_etapas`, `sa_vinculos_avaliacoes_descritivas`, `sa_faixas_de_proficiencia`, `sa_solicitacoes_de_calculo_de_notas`, `sa_notas_oriundas_de_transferencia`, `sa_notas_pos_transferencia`, `sa_notas_e_faltas_transferencias_externas`, `sa_notas_avancos`, `sa_faltas_avulsas_ata_de_resultado_final`.

---

## 6. Frequencia (4 tabelas)

Registro de presencas, faltas e justificativas.

```mermaid
erDiagram
    sa_frequencias {
        int id PK
        bool bool_ausente
        int aula_id
        int enturmacao_id FK
        int professor_id FK
        int turma_id FK
        int disciplina_id FK
        int tipo_de_justificativa_de_falta_id FK
        int justificativa_de_falta_id FK
    }

    sa_justificativas_de_faltas {
        int id PK
        int enturmacao_id FK
        int tipo_de_justificativa_de_falta_id FK
        timestamp dt_inicial
        timestamp dt_final
        text str_justificativa
        int etapa_de_abono_id FK
        bool bool_falta_abonada
    }

    sa_tipos_de_justificativas_de_faltas {
        int id PK
        varchar str_nome
        bool bool_inativo
    }

    sa_historico_aulas {
        int id PK
        text str_conteudo
        text str_observacao
        bool bool_eletiva
        datetime dt_aula
        int turma_id FK
        int disciplina_id FK
        int horario_aula_id FK
        int professor_id FK
        int usuario_registro_observacao_id FK
        int historico_geracao_de_aula_id FK "NOVO SISP-9853 - nullable"
    }

    sa_historico_geracao_de_aulas {
        int id PK
        datetime dt_geracao_das_aulas
        int horario_calendario_id FK
        int horario_calendario_eletiva_id FK
        int horario_calendario_estudo_complementar_id FK
        int usuario_id FK
        int disciplina_id FK
        int turma_id FK "NOVO SISP-9853 - nullable"
    }

    sa_historico_faltas {
        int id PK
        int historico_aula_id FK
        int enturmacao_id FK
        int professor_id FK
        int tipo_de_justificativa_de_falta_id FK
    }

    sa_historico_aulas ||--o{ sa_historico_faltas : "faltas do historico"
    sa_historico_geracao_de_aulas ||--o{ sa_historico_aulas : "aulas arquivadas (NOVO SISP-9853)"
    sa_historico_geracao_de_aulas ||--o{ sa_aulas : "aulas geradas (NOVO SISP-9853)"
    sa_historico_geracao_de_aulas }o--|| sa_turmas : "turma (NOVO SISP-9853)"

    sa_enturmacoes ||--o{ sa_frequencias : "frequencia do aluno"
    sa_tipos_de_justificativas_de_faltas ||--o{ sa_frequencias : "tipo justificativa"
    sa_tipos_de_justificativas_de_faltas ||--o{ sa_justificativas_de_faltas : "tipo justificativa"
    sa_enturmacoes ||--o{ sa_justificativas_de_faltas : "justificativas"
```

---

## 7. Aulas e Horarios (18 tabelas)

Registro de aulas, horarios, calendarios academicos e escolares.

```mermaid
erDiagram
    sa_aulas {
        int id PK
        varchar str_conteudo
        timestamp dt_aula
        int disciplina_id FK
        int turma_id FK
        int professor_id FK
        int horario_aula_id FK
        bool bool_aula_dada
        bool bool_eletiva
        int historico_geracao_de_aula_id FK "NOVO SISP-9853 - nullable"
    }

    sa_horarios {
        int id PK
        varchar str_nome
        int turno_id FK
        int int_minutos_hora_aula
    }

    sa_horarios_aulas {
        int id PK
        varchar str_hora_inicial
        varchar str_hora_final
        int int_sequencial_aula
        int horario_id FK
        int int_dia_da_semana
    }

    sa_calendarios_academicos {
        int id PK
        int turma_id FK
        int ano_de_escolaridade_id FK
        int periodo_letivo_id FK
        int curso_id FK
        int unidade_id FK
        int turno_id FK
    }

    sa_horarios_calendarios {
        int id PK
        int calendario_academico_id FK
        int disciplina_id FK
        int horario_aula_id FK
    }

    sa_calendarios_escolares {
        int id PK
        varchar str_nome
        bool bool_ativo
        timestamp dt_inicio
        timestamp dt_termino
        int periodo_letivo_id FK
        int curso_id FK
        int unidade_id FK
    }

    sa_calendarios_professores {
        int id PK
        bool bool_principal
        int professor_id FK
        int calendario_academico_id FK
        int disciplina_id FK
        bool bool_permitir_lancamentos
    }

    sa_horarios ||--o{ sa_horarios_aulas : "aulas do horario"
    sa_horarios_aulas ||--o{ sa_aulas : "aula no horario"
    sa_calendarios_academicos ||--o{ sa_horarios_calendarios : "grade horaria"
    sa_calendarios_academicos ||--o{ sa_calendarios_professores : "professores"

    sa_aulas }o--|| sa_disciplinas : "disciplina"
    sa_aulas }o--|| sa_turmas : "turma"
    sa_aulas }o--|| sa_professores : "professor"
```

Tabelas complementares: `sa_aulas_complementares`, `sa_calendarios_academicos_eletivas`, `sa_calendarios_escolares_periodos_letivos_cursos`, `sa_horarios_calendarios_eletivas`, `sa_horarios_calendario_de_estudo_complementar`, `sa_horarios_quadros_de_atendimento`, `sa_quadros_de_atendimento`, `sa_atividades_complementares`, `sa_historico_disciplinas_horarios_calendarios`.

---

## 8. Profissionais (12 tabelas)

Professores, diretores, coordenadores e demais profissionais da educacao.

```mermaid
erDiagram
    sa_professores {
        int id PK
        int pessoa_id FK
        bool bool_ativo
    }

    sa_professores_especialistas {
        int id PK
        int pessoa_id FK
        bool bool_ativo
    }

    sa_diretores {
        int id PK
        int pessoa_id FK
        bool bool_ativo
    }

    sa_secretarios {
        int id PK
        int pessoa_id FK
        bool bool_ativo
        varchar str_autorizacao
    }

    sa_coordenadores_de_ensino {
        int id PK
        int pessoa_id FK
        bool bool_ativo
    }

    sa_inspetores_escolares {
        int id PK
        int pessoa_id FK
        bool bool_ativo
    }

    sa_pedagogos {
        int id PK
        int pessoa_id FK
        bool bool_ativo
    }

    sa_monitores {
        int id PK
        int pessoa_id FK
        bool bool_ativo
        int int_forma_de_ingresso
    }

    sa_auxiliares_de_secretaria {
        int id PK
        int pessoa_id FK
        bool bool_ativo
    }

    sa_gestores {
        int id PK
        int pessoa_id FK
        bool bool_ativo
    }

    sa_professores }o--|| sg_pessoas : "pessoa_id - Gerenciador"
    sa_diretores }o--|| sg_pessoas : "pessoa_id - Gerenciador"
    sa_secretarios }o--|| sg_pessoas : "pessoa_id - Gerenciador"
    sa_coordenadores_de_ensino }o--|| sg_pessoas : "pessoa_id - Gerenciador"
    sa_pedagogos }o--|| sg_pessoas : "pessoa_id - Gerenciador"
    sa_monitores }o--|| sg_pessoas : "pessoa_id - Gerenciador"
```

Tabelas adicionais: `sa_professores_secundarios`, `sa_professores_calendarios_eletivas`.

---

## 9. Movimentacao de Alunos (15 tabelas)

Transferencias, cancelamentos, desistencias, evasoes, avancos e encaminhamentos.

```mermaid
erDiagram
    sa_pedidos_de_transferencia {
        int id PK
        int int_status
        varchar str_justificativa
        timestamp dt_transferencia
        int enturmacao_origem_id FK
        int enturmacao_destino_id FK
        int unidade_origem_id FK
        int unidade_destino_id FK
        bool bool_aceite_unidade_origem
        bool bool_aceite_unidade_destino
    }

    sa_transferencias_externas {
        int id PK
        varchar str_motivo
        timestamp dt_transferencia
        int enturmacao_id FK
        int usuario_executor_id FK
    }

    sa_transferencias_entre_cursos {
        int id PK
        text str_justificativa
        date dt_transferencia
        int enturmacao_origem_id FK
        int enturmacao_destino_id FK
        int usuario_executor_id FK
    }

    sa_cancelamentos {
        int id PK
        int enturmacao_id FK
        timestamp dt_saida
        text str_motivo
        int usuario_id FK
    }

    sa_desistencias {
        int id PK
        int enturmacao_id FK
        timestamp dt_saida
        text str_motivo
    }

    sa_evasoes {
        int id PK
        int enturmacao_id FK
        timestamp dt_saida
        text str_motivo
    }

    sa_avancos {
        int id PK
        varchar str_parecer
        int enturmacao_origem_id FK
        int enturmacao_destino_id FK
        int int_tipo_avanco
    }

    sa_encaminhamentos_entre_periodos_letivos {
        int id PK
        int int_situacao
        int enturmacao_origem_id FK
        int periodo_letivo_destino_id FK
        int ano_de_escolaridade_destino_id FK
        int unidade_destino_id FK
    }

    sa_regras_de_encaminhamento {
        int id PK
        int periodo_letivo_id FK
        int unidade_origem_id FK
        int ano_de_escolaridade_origem_id FK
        int ano_de_escolaridade_destino_id FK
        int periodo_letivo_destino_id FK
    }

    sa_enturmacoes ||--o{ sa_pedidos_de_transferencia : "origem/destino"
    sa_enturmacoes ||--o{ sa_transferencias_externas : "transferencia"
    sa_enturmacoes ||--o{ sa_cancelamentos : "cancelamento"
    sa_enturmacoes ||--o{ sa_desistencias : "desistencia"
    sa_enturmacoes ||--o{ sa_evasoes : "evasao"
    sa_enturmacoes ||--o{ sa_avancos : "avanco"
    sa_enturmacoes ||--o{ sa_encaminhamentos_entre_periodos_letivos : "encaminhamento"
```

Tabelas complementares: `sa_pedidos_de_transferencia_responsavel`, `sa_anexos_do_avanco`, `sa_notas_avancos`, `sa_regras_de_encaminhamento_necessidade_especial`, `sa_regras_de_encaminhamento_unidade`, `sa_historico_remanejamentos`, `sa_status_pedidos_de_transferencia_responsavel`.

---

## 10. Ambiente Virtual de Aprendizagem (16 tabelas)

Aulas online, atividades online, conteudos e respostas.

```mermaid
erDiagram
    sa_aulas_online {
        int id PK
        varchar str_nome
        varchar str_resumo
        timestamp dt_abertura
        int professor_id FK
        int periodo_letivo_id FK
        int ano_de_escolaridade_id FK
        int etapa_id FK
        int disciplina_id FK
    }

    sa_atividades_online {
        int id PK
        varchar str_nome
        bool bool_possui_nota
        float float_valor
        timestamp dt_abertura
        timestamp dt_encerramento
        int professor_id FK
        int disciplina_id FK
        int etapa_id FK
    }

    sa_aulas_online_turmas {
        int aula_online_id FK
        int turma_id FK
    }

    sa_atividades_online_turmas {
        int id PK
        int turma_id FK
        int atividade_online_id FK
        int avaliacao_id FK
    }

    sa_conteudos_aulas_online {
        int id PK
        varchar str_nome
        int int_tipo
        int int_ordem
        int aula_online_id FK
    }

    sa_perguntas_atividades_online {
        int id PK
        varchar str_titulo
        int int_tipo_resposta
        float float_valor
        int atividade_online_id FK
    }

    sa_respostas_atividades_online {
        int id PK
        float float_nota
        int pergunta_atividade_online_id FK
        int enturmacao_id FK
        int professor_id FK
    }

    sa_progressos_atividades_online {
        int id PK
        int atividade_online_id FK
        int enturmacao_id FK
        int int_situacao
        bool bool_corrigida
        float float_nota_obtida
    }

    sa_aulas_online ||--o{ sa_aulas_online_turmas : "turmas"
    sa_aulas_online ||--o{ sa_conteudos_aulas_online : "conteudos"
    sa_atividades_online ||--o{ sa_atividades_online_turmas : "turmas"
    sa_atividades_online ||--o{ sa_perguntas_atividades_online : "perguntas"
    sa_perguntas_atividades_online ||--o{ sa_respostas_atividades_online : "respostas"
    sa_atividades_online ||--o{ sa_progressos_atividades_online : "progressos"
```

Tabelas complementares: `sa_acessos_aulas_online`, `sa_bloqueios_de_atividades_online`, `sa_controle_de_tempo_atividades_online`, `sa_controle_de_tempo_aulas_online`, `sa_opcoes_de_resposta_atividades_online`, `sa_conteudos_perguntas_atividades_online`, `sa_anexos_respostas_atividades_online`, `sa_conquistas_gamificacao_do_ava`.

---

## 11. Planejamento de Aulas (25 tabelas)

Planos de ensino e planejamentos pedagogicos dos professores.

```mermaid
erDiagram
    sa_planos_de_ensino {
        int id PK
        varchar str_nome
        int periodo_letivo_id FK
        int curso_id FK
        bool bool_ativo
    }

    sa_planejamentos_de_aulas {
        int id PK
        varchar str_nome
        int int_situacao
        int periodo_letivo_id FK
        int professor_id FK
        int turma_id FK
        int etapa_id FK
        date dt_inicial
        date dt_final
    }

    sa_quesitos_do_plano_de_ensino {
        int id PK
        text str_descricao
        int plano_de_ensino_id FK
        int int_ordem
        bool bool_quesito_agrupador
    }

    sa_respostas_planos_de_ensino {
        int id PK
        int int_situacao
        int plano_de_ensino_id FK
        int usuario_id FK
        int turma_id FK
        int disciplina_id FK
        int etapa_id FK
    }

    sa_historico_planejamentos_de_aulas {
        int id PK
        datetime dt_situacao
        int int_situacao_anterior
        int int_situacao_atual
        int planejamento_de_aulas_id FK
        text str_observacao
        int usuario_id FK
    }

    sa_planos_de_ensino ||--o{ sa_quesitos_do_plano_de_ensino : "quesitos"
    sa_planos_de_ensino ||--o{ sa_respostas_planos_de_ensino : "respostas"
    sa_planejamentos_de_aulas }o--|| sa_turmas : "turma"
    sa_planejamentos_de_aulas }o--|| sa_professores : "professor"
    sa_planejamentos_de_aulas ||--o{ sa_historico_planejamentos_de_aulas : "historico"
    sg_usuarios ||--o{ sa_historico_planejamentos_de_aulas : "validou"
```

Sub-tabelas do planejamento: `sa_planejamentos_de_aulas_acolhidas`, `sa_planejamentos_de_aulas_avaliacoes_e_orientacoes`, `sa_planejamentos_de_aulas_competencias_especificas`, `sa_planejamentos_de_aulas_competencias_gerais`, `sa_planejamentos_de_aulas_datas`, `sa_planejamentos_de_aulas_disciplinas`, `sa_planejamentos_de_aulas_habilidades_especificas`, `sa_planejamentos_de_aulas_habilidades_gerais`, `sa_planejamentos_de_aulas_imagens`, `sa_planejamentos_de_aulas_observacoes`, `sa_planejamentos_de_aulas_praticas_de_linguagem`, `sa_planejamentos_de_aulas_praticas_sociais`, `sa_planejamentos_de_aulas_rotinas`, `sa_planejamentos_de_aulas_rotinas_diarias`, `sa_planejamentos_de_aulas_vivencias`.

Todas vinculadas a `sa_planejamentos_de_aulas` via `planejamento_de_aulas_id` FK.

Tabelas auxiliares: `sa_planos_de_ensino_individualizado`, `sa_respostas_quesitos_planos_de_ensino`, `sa_anexos_do_plano_de_ensino`, `sa_historico_planos_de_ensino`, `sa_historico_planejamentos_de_aulas`, `sa_rotinas_diarias`, `sa_planejamento_de_aulas_matrizes_de_descritores`.

---

## 12. Avaliacoes Especiais (33 tabelas)

Anamnese, avaliacao diagnostica, PEI (Plano Educacional Individualizado) e PDI (Plano de Desenvolvimento Individual). Seguem padrao: formulario > quesitos > perguntas > opcoes > respostas > anexos.

```mermaid
erDiagram
    sa_anamneses {
        int id PK
        varchar str_nome
    }

    sa_avaliacoes_anamnese {
        int id PK
        int anamnese_id FK
        int aluno_id FK
        int periodo_letivo_id FK
    }

    sa_avaliacoes_diagnostica {
        int id PK
        varchar str_nome
        int periodo_letivo_id FK
        int etapa_id FK
        int curso_id FK
    }

    sa_avaliacoes_pei {
        int id PK
        int pei_id FK
        int aluno_id FK
        int periodo_letivo_id FK
    }

    sa_formularios_pdi {
        int id PK
        varchar str_nome
        int periodo_letivo_id FK
        int etapa_id FK
        int curso_id FK
    }

    sa_planos_de_ensino_individualizado {
        int id PK
        varchar str_nome
        int periodo_letivo_id FK
        int curso_id FK
        int etapa_id FK
    }

    sa_anamneses ||--o{ sa_avaliacoes_anamnese : "avaliacoes"
    sa_avaliacoes_diagnostica ||--o{ sa_avaliacoes_avaliacao_diagnostica : "avaliacoes"
    sa_formularios_pdi ||--o{ sa_avaliacoes_formulario_pdi : "avaliacoes"
```

**Padrao de tabelas por formulario (cada um segue a mesma estrutura):**

| Formulario | Quesitos | Perguntas | Opcoes | Respostas | Anexos |
|---|---|---|---|---|---|
| `sa_anamneses` | `sa_quesitos_anamnese` | `sa_perguntas_anamnese` | `sa_opcoes_resposta_anamnese` | `sa_respostas_anamnese` | `sa_anexos_da_anamnese` |
| `sa_avaliacoes_diagnostica` | `sa_quesitos_avaliacao_diagnostica` | `sa_perguntas_avaliacao_diagnostica` | `sa_opcoes_resposta_avaliacao_diagnostica` | `sa_respostas_avaliacao_diagnostica` | `sa_anexos_da_avaliacao_diagnostica` |
| (PEI) | `sa_quesitos_pei` | `sa_perguntas_pei` | `sa_opcoes_resposta_pei` | `sa_respostas_pei` | `sa_anexos_do_pei` |
| `sa_formularios_pdi` | `sa_quesitos_formulario_pdi` | `sa_perguntas_formulario_pdi` | `sa_opcoes_resposta_formulario_pdi` | `sa_respostas_formulario_pdi` | `sa_anexos_da_pdi` |

Tabelas adicionais: `sa_avaliacoes_anamnese_dos_alunos`, `sa_quesitos_da_anamnese`, `sa_respostas_dos_quesitos_anamnese`, `sa_grupos_de_quesitos_da_anamnese`, `sa_formularios_anamnese`, `sa_avaliacoes_avaliacao_diagnostica`, `sa_avaliacoes_formulario_pdi`.

---

## 13. Banco de Questoes (7 tabelas)

Repositorio de questoes para atividades online.

```mermaid
erDiagram
    sa_questoes_banco_de_questoes {
        int id PK
        varchar str_titulo
        int int_tipo_resposta
        int conteudo_banco_de_questoes_id FK
        int topico_banco_de_questoes_id FK
        int subtopico_banco_de_questoes_id FK
        int nivel_de_dificuldade_banco_de_questoes_id FK
        int disciplina_id FK
        int curso_id FK
    }

    sa_conteudos_banco_de_questoes {
        int id PK
        varchar str_nome
        int disciplina_id FK
    }

    sa_topicos_banco_de_questoes {
        int id PK
        varchar str_nome
        int conteudo_banco_de_questoes_id FK
    }

    sa_subtopicos_banco_de_questoes {
        int id PK
        varchar str_nome
        int topico_banco_de_questoes_id FK
    }

    sa_niveis_de_dificuldade_banco_de_questoes {
        int id PK
        varchar str_nome
    }

    sa_opcoes_de_resposta_banco_de_questoes {
        int id PK
        text str_conteudo
        bool bool_resposta_correta
        int questao_banco_de_questoes_id FK
    }

    sa_conteudos_da_questao_banco_de_questoes {
        int id PK
        int int_tipo
        text str_conteudo
        int questao_banco_de_questoes_id FK
    }

    sa_conteudos_banco_de_questoes ||--o{ sa_topicos_banco_de_questoes : "topicos"
    sa_topicos_banco_de_questoes ||--o{ sa_subtopicos_banco_de_questoes : "subtopicos"
    sa_questoes_banco_de_questoes ||--o{ sa_opcoes_de_resposta_banco_de_questoes : "opcoes"
    sa_questoes_banco_de_questoes ||--o{ sa_conteudos_da_questao_banco_de_questoes : "conteudos"
    sa_conteudos_banco_de_questoes ||--o{ sa_questoes_banco_de_questoes : "questoes"
    sa_niveis_de_dificuldade_banco_de_questoes ||--o{ sa_questoes_banco_de_questoes : "nivel"
```

---

## 14. Historico Escolar (5 tabelas)

Documentacao do historico escolar do aluno.

```mermaid
erDiagram
    sa_historicos_escolares {
        int id PK
        int aluno_id FK
        int unidade_id FK
        int curso_id FK
        varchar str_unidade
        varchar str_curso
        text str_sistema_de_avaliacao
    }

    sa_historicos_escolares_anos_de_escolaridade {
        int id PK
        varchar str_ano_de_escolaridade
        varchar str_turma
        varchar str_turno
        varchar str_ano_letivo
        varchar str_resultado_final
        int int_carga_horaria_total
        int int_total_faltas
        int historico_escolar_id FK
        int enturmacao_id FK
    }

    sa_historicos_escolares_disciplinas {
        int id PK
        varchar str_disciplina
        int int_faltas
        float float_notas
        int disciplina_id FK
        int historico_ano_de_escolaridade_id FK
        int int_carga_horaria
    }

    sa_disciplinas_do_historico {
        int id PK
        varchar str_nome
        int area_de_conhecimento_id FK
    }

    sa_resolucoes {
        int id PK
        int unidade_id FK
        int curso_id FK
        text str_resolucao
        text str_autorizacao
    }

    sa_historicos_escolares ||--o{ sa_historicos_escolares_anos_de_escolaridade : "anos"
    sa_historicos_escolares_anos_de_escolaridade ||--o{ sa_historicos_escolares_disciplinas : "disciplinas"
    sa_alunos ||--o{ sa_historicos_escolares : "historicos"
```

---

## 15. Conselho de Classe, Eventos e Ocorrencias (15 tabelas)

Conselhos de classe, eventos escolares, ocorrencias e atendimento especializado.

```mermaid
erDiagram
    sa_equipes_de_conselho_de_classe {
        int id PK
        varchar str_nome
        int turma_id FK
        int etapa_id FK
    }

    sa_membros_da_equipe_de_conselho_de_classe {
        int id PK
        int int_tipo_de_membro
        int usuario_id FK
        int equipe_de_conselho_de_classe_id FK
    }

    sa_registros_conselho_de_classe {
        int id PK
        float float_nota
        int int_faltas
        varchar str_parecer
        int disciplina_id FK
        int etapa_id FK
        int enturmacao_id FK
    }

    sa_conceitos_conselho_de_classe {
        int id PK
        varchar str_nome
        int int_tipo_de_resposta
        int etapa_id FK
    }

    sa_eventos_escolares {
        int id PK
        varchar str_nome
        timestamp dt_evento
        bool bool_dia_nao_letivo
        int calendario_escolar_id FK
        int tipo_de_evento_escolar_id FK
    }

    sa_ocorrencias {
        int id PK
        int enturmacao_id FK
        date dt_ocorrencia
        int tipo_de_ocorrencia_id FK
        varchar str_observacao
        int disciplina_id FK
        int usuario_id FK
    }

    sa_tipos_de_ocorrencias {
        int id PK
        varchar str_nome
        int int_categoria
    }

    sa_tipos_de_eventos_escolares {
        int id PK
        varchar str_nome
        varchar str_cor
    }

    sa_agendamento_de_atendimento_especializado {
        int id PK
        int periodo_letivo_id FK
        int unidade_id FK
        int turma_id FK
        int aluno_id FK
        varchar str_assunto
    }

    sa_equipes_de_conselho_de_classe ||--o{ sa_membros_da_equipe_de_conselho_de_classe : "membros"
    sa_tipos_de_ocorrencias ||--o{ sa_ocorrencias : "tipo"
    sa_tipos_de_eventos_escolares ||--o{ sa_eventos_escolares : "tipo"
    sa_enturmacoes ||--o{ sa_registros_conselho_de_classe : "registro"
    sa_enturmacoes ||--o{ sa_ocorrencias : "ocorrencia"
```

Tabelas complementares: `sa_conceitos_conselho_de_classe_anos_de_escolaridade`, `sa_respostas_conceitos_conselho_de_classe`, `sa_eventos_escolares_unidades`, `sa_grupos_de_eventos_escolares`, `sa_registro_de_atendimento_especializado`, `sa_anexos_do_atendimento_educacional_especializado`.

---

## 16. Documentos e Configuracoes (15 tabelas)

Modelos de documentos, assinaturas digitais, carteirinhas e rematricula.

```mermaid
erDiagram
    sa_modelos_de_documentos {
        int id PK
        varchar str_nome
        int int_tipo_de_documento
        bool bool_ativo
        varchar str_path_modelo
    }

    sa_documentos_digitais {
        int id PK
        varchar str_descricao
        varchar str_tipo_documento
        int usuario_id FK
        bool bool_assinado
    }

    sa_documentos_digitais_assinaturas {
        int id PK
        int documento_digital_id FK
        int usuario_id FK
        bool bool_assinado
    }

    sa_layouts_carteirinhas {
        int id PK
        varchar str_nome
        bool bool_ativo
        int int_tipo_de_papel
        bool bool_padrao
    }

    sa_registros_rematricula {
        int id PK
        int enturmacao_id FK
        int usuario_id FK
        bool bool_aceito
        int int_situacao
        bool bool_rematricula_online
    }

    sa_campos_personalizados {
        int id PK
        varchar str_pergunta
        int int_tipo_resposta
        int secao_campo_personalizado_id FK
        bool bool_ativo
    }

    sa_documentos_digitais ||--o{ sa_documentos_digitais_assinaturas : "assinaturas"
    sa_layouts_carteirinhas ||--o{ sa_layouts_de_carteirinhas_unidades : "unidades"
    sa_layouts_carteirinhas ||--o{ sa_configuracoes_campos_carteirinhas : "campos"
```

Tabelas complementares: `sa_modelos_de_relatorios`, `sa_configuracoes_de_modelo_de_documento`, `sa_assinaturas_configuracao_modelo_de_documento`, `sa_layouts_de_carteirinhas_unidades`, `sa_configuracoes_campos_carteirinhas`, `sa_secoes_campos_personalizados`, `sa_respostas_campos_personalizados`, `sa_motivos_de_rejeicao_registro_rematricula`, `sa_tipos_de_documentos_rematricula`.

---

## 17. Tabelas Auxiliares e Quesitos (18 tabelas)

Tipos, quesitos de avaliacao descritiva, gamificacao e tabelas de apoio.

| Tabela | Descricao | Colunas Principais |
|---|---|---|
| `sa_tipos_de_documento` | Tipos de documentos do aluno | `id`, `str_nome`, `bool_sexo_masculino`, `bool_sexo_feminino` |
| `sa_tipos_de_documentos_enturmacoes` | Documentos por enturmacao | `enturmacao_id` FK, `tipo_de_documento_id` FK |
| `sa_tipos_de_papel` | Tamanhos de papel para impressao | `id`, `str_nome`, `int_altura`, `int_largura` |
| `sa_tipos_de_turnos` | Tipos de turno | `id`, `str_nome` |
| `sa_tipos_de_unidades` | Tipos de unidade escolar | `id`, `str_nome` |
| `sa_etnias` | Etnias | `id`, `str_nome` |
| `sa_base_de_conhecimento` | Areas do conhecimento (referencia) | `id`, `str_nome` |
| `sa_cargos_da_comissao` | Cargos da comissao de eleicao | `id`, `str_nome`, `int_perfil_de_acesso` |
| `sa_quesitos` | Quesitos de avaliacao descritiva | `id`, `str_pergunta`, `int_tipo_resposta`, `bool_obrigatorio` |
| `sa_grupos_de_quesitos` | Agrupamento de quesitos | `id`, `str_nome`, `avaliacao_descritiva_id` FK |
| `sa_grupos_de_quesitos_disciplinas` | Quesitos por disciplina | `disciplina_id` FK, `grupo_de_quesitos_id` FK |
| `sa_grupos_de_quesitos_quesitos` | Quesitos no grupo | `quesito_id` FK, `grupo_de_quesitos_id` FK |
| `sa_respostas_quesitos` | Respostas de avaliacao descritiva | `enturmacao_id` FK, `quesito_id` FK, `etapa_id` FK |
| `sa_respostas_quesitos_por_turma` | Respostas por turma | `quesito_id` FK, `turma_id` FK, `etapa_id` FK |
| `sa_respostas_fichas_de_acompanhamento` | Fichas de acompanhamento | `enturmacao_id` FK, `usuario_id` FK |
| `sa_gamificacoes_do_aluno` | Configuracao de gamificacao | `id`, `str_nome`, `periodo_letivo_id` FK |
| `sa_conquistas_gamificacao_do_aluno` | Conquistas do aluno | `enturmacao_id` FK, `disciplina_id` FK, `etapa_id` FK |
| `sa_faixas_de_gamificacao_do_aluno` | Faixas de pontuacao | `curso_id` FK, `etapa_id` FK, `float_faixa_inicial`, `float_faixa_final` |

---

## 18. Tabelas de Censo (33 tabelas)

Tabelas anuais para exportacao de dados ao Censo Escolar (INEP). Seguem padrao `sa_censo_{tipo}_{ano}_fase{n}`.

| Tipo | Anos | Tabelas |
|---|---|---|
| `sa_censo_alunos` | 2021-2025 | 5 tabelas (fase 1) |
| `sa_censo_gestores` | 2021-2025 | 5 tabelas (fase 1) |
| `sa_censo_pessoas_fisicas` | 2021-2025 | 5 tabelas (fase 1) |
| `sa_censo_profissionais_escolares` | 2021-2025 | 5 tabelas (fase 1) |
| `sa_censo_turmas` | 2021-2025 | 5 tabelas (fase 1) |
| `sa_censo_unidades` | 2021-2025 | 5 tabelas (fase 1) |
| `sa_censo2021_arquivo*_fase2` | 2021 | 3 tabelas (fase 2) |

---

## 19. Portal do Professor - Grupos de Aulas e Faltas Diarias (2 novas + 1 modificada)

Novas entidades para grupos de aulas e faltas diarias desvinculadas de aulas.

> **Fluxo principal:** Professor registra um grupo de aulas (com ou sem horario definido) e, de forma independente, registra faltas diarias por aluno/turma sem necessidade de vincular a uma aula especifica.

```mermaid
erDiagram
    sa_grupos_de_aulas {
        int id PK
        timestamp dt_aula "Data do grupo de aulas"
        int turma_id FK
        int professor_id FK
        int horario_aula_id FK "nullable quando sem horario"
        text str_conteudo "Conteudo da aula registrado pelo professor"
        text str_observacao "Observacao opcional"
    }

    sa_faltas_diarias {
        int id PK
        date dt_falta "Data da falta"
        int enturmacao_id FK
        int turma_id FK
        int professor_id FK
        bool bool_ausente "true = falta, false = presenca"
        int tipo_de_justificativa_de_falta_id FK "nullable"
        int justificativa_de_falta_id FK "nullable"
    }

    sa_turmas ||--o{ sa_grupos_de_aulas : "grupos da turma"
    sa_professores ||--o{ sa_grupos_de_aulas : "grupos do professor"
    sa_horarios_aulas ||--o{ sa_grupos_de_aulas : "horario do grupo"
    sa_grupos_de_aulas ||--o{ sa_aulas : "aulas do grupo"
    sa_enturmacoes ||--o{ sa_faltas_diarias : "faltas do aluno"
    sa_turmas ||--o{ sa_faltas_diarias : "faltas da turma"
    sa_professores ||--o{ sa_faltas_diarias : "faltas registradas"
    sa_tipos_de_justificativas_de_faltas ||--o{ sa_faltas_diarias : "tipo justificativa"
    sa_justificativas_de_faltas ||--o{ sa_faltas_diarias : "justificativa aplicada"
```

**Campo novo em `sa_aulas`:** `grupo_de_aula_id` (int FK nullable) — associa aulas a um grupo. Nullable para retrocompatibilidade com aulas existentes.

**Notas de retrocompatibilidade:**
- `sa_frequencias` permanece inalterada para frequencia vinculada a aula (mecanismo original)
- `sa_faltas_diarias` e o novo mecanismo para faltas independentes de aula
- Quando `sa_grupos_de_aulas.bool_sem_horario = true`, o campo `horario_aula_id` fica `null`

---

## 20. Importacao Legado SISLAME (6 tabelas)

Dados importados do sistema legado SISLAME: ata de resultado final, diario de classe (notas, frequencia, conteudo) e carga horaria. Tabelas independentes sem FK para o SISP — usam IDs do SISLAME como referencia.

```mermaid
erDiagram
    sa_carga_horaria_ata_legado {
        bigint id PK
        int int_turma_id_sislame
        varchar str_programa_pedagogico
        int int_programa_pedagogico_item_id_sislame
        float float_carga_horaria
    }

    sa_resultado_ata_legado {
        bigint id PK
        int int_turma_id_sislame
        varchar str_programa_pedagogico
        int int_aluno_id_sislame
        int int_programa_pedagogico_item_id_sislame
        float float_nota
        varchar str_conceito
        int int_faltas
    }

    sa_diario_consolidado_legado {
        bigint id PK
        int int_turma_id_sislame
        int int_programa_pedagogico_item_id_sislame
        int int_aluno_id_sislame
        int int_divisao_id_sislame
        float float_nota
        varchar str_conceito
        int int_faltas
    }

    sa_diario_notas_legado {
        bigint id PK
        int int_turma_id_sislame
        int int_programa_pedagogico_item_id_sislame
        int int_divisao_id_sislame
        int int_aluno_id_sislame
        int int_avaliacao_id_sislame
        float float_nota
        varchar str_conceito
    }

    sa_diario_frequencia_legado {
        bigint id PK
        int int_turma_id_sislame
        int int_programa_pedagogico_item_id_sislame
        int int_divisao_id_sislame
        int int_aluno_id_sislame
        date dt_aula
        bool bool_presenca
    }

    sa_diario_conteudo_legado {
        bigint id PK
        int int_turma_id_sislame
        int int_programa_pedagogico_item_id_sislame
        int int_divisao_id_sislame
        date dt_aula
        int int_ordem
        text str_plano_aula
    }
```

**Nota:** Todas as tabelas usam `int_turma_id_sislame` e `int_programa_pedagogico_item_id_sislame` como chaves de referencia ao sistema legado. Nao possuem foreign keys para tabelas do SISP.

---

## Resumo

| # | Grupo | Tabelas | Descricao |
|---|---|---|---|
| 1 | Alunos e Responsaveis | 14 | Cadastro, deficiencias, documentacao |
| 2 | Unidades e Estrutura | 18 | Escolas, cursos, periodos, turnos |
| 3 | Turmas e Enturmacoes | 12 | Turmas, enturmacao, vinculos |
| 4 | Disciplinas e Grades | 13 | Disciplinas, grades curriculares, etapas |
| 5 | Notas e Avaliacoes | 20 | Lancamento de notas, arredondamentos |
| 6 | Frequencia | 4 | Presencas, faltas, justificativas |
| 7 | Aulas e Horarios | 18 | Aulas, horarios, calendarios |
| 8 | Profissionais | 12 | Professores, diretores, coordenadores |
| 9 | Movimentacao | 15 | Transferencias, cancelamentos, evasoes |
| 10 | Ambiente Virtual | 16 | Aulas online, atividades, respostas |
| 11 | Planejamento | 24 | Planos de ensino, planejamentos |
| 12 | Avaliacoes Especiais | 33 | Anamnese, diagnostica, PEI, PDI |
| 13 | Banco de Questoes | 7 | Repositorio de questoes |
| 14 | Historico Escolar | 5 | Documentacao do historico |
| 15 | Conselho e Eventos | 15 | Conselhos, eventos, ocorrencias |
| 16 | Documentos e Config | 15 | Modelos, assinaturas, carteirinhas |
| 17 | Auxiliares e Quesitos | 18 | Tipos, quesitos, gamificacao |
| 18 | Censo | 33 | Exportacao dados INEP |
| 19 | Portal do Professor | 2+1 | Grupos de aulas, faltas diarias |
| 20 | Legado SISLAME | 6 | Importacao dados sistema legado |
| | **TOTAL** | **301** | |
