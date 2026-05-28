# ER - Escopo Completo do Aluno

Visao centrada no aluno: pessoa, endereco, estrutura academica, responsaveis, enturmacoes, notas, faltas e todas as tabelas que referenciam diretamente `sa_alunos` ou `sa_enturmacoes`.

> Campos de auditoria (`criado_em`, `atualizado_em`, `removido_em`) omitidos por brevidade.

---

## 1. Pessoa e Endereco (Modulo Gerenciador)

Dados pessoais, endereco, telefone e documentos. Compartilhados entre aluno e responsavel via `pessoa_id`.

```mermaid
erDiagram
    sg_pessoas {
        int id PK
        string str_nome
        string str_nome_social
        string str_cpf
        string str_email
        date data_nascimento
        int int_genero
        int int_cor_raca
        int int_estado_civil
        int int_grau_de_escolaridade
        string str_filiacao_1
        string str_filiacao_2
        int int_genero_filiacao_1
        int int_genero_filiacao_2
        boolean bool_filiacao_1_falecida
        boolean bool_filiacao_2_falecida
        string str_naturalidade
        string str_nacionalidade
        string str_certidao_de_nascimento_modelo_novo
        string str_certidao_de_nascimento_modelo_antigo
        string str_numero_cartao_do_sus
        string str_pis_pasep
        string str_ctps
        string str_titulo_de_eleitor
        boolean bool_possui_laudo_necessidade_especial
        boolean bool_usuario_emancipado
        int necessidade_especial_id FK
        int etnia_id FK
        int nacionalidade_id FK
        int naturalidade_id FK
        int uf_naturalidade_id FK
        int profissao_id FK
    }

    sg_enderecos {
        int id PK
        string str_cep
        string str_logradouro
        string str_numero
        string str_complemento
        string str_bairro
        string str_cidade
        int int_zona
        string str_numero_instalacao_eletrica
        int estado_id FK
        int bairro_id FK
        int cidade_id FK
        int pessoa_id FK
    }

    sg_rgs {
        int id PK
        string str_rg
        string str_orgao_emissor
        timestamp dt_emissao
        int estado_emissao_id FK
        int pessoa_id FK
    }

    sg_telefones {
        int id PK
        string str_numero
        string str_observacao
        boolean bool_telefone_para_recuperacao_de_senha
        int tipo_telefone_id FK
        int pessoa_id FK
    }

    sg_tipos_telefones {
        int id PK
        string str_nome
    }

    sg_necessidades_especiais {
        int id PK
        string str_nome
        boolean bool_frequentar_sala_aee_sem_laudo
    }

    sa_etnias {
        int id PK
        string str_nome
    }

    sg_paises {
        int id PK
        string str_nome
        string str_nacionalidade
        int int_codigo_inep
        int int_ddi
    }

    sg_estados {
        int id PK
        string str_nome
        string str_sigla
        string str_regiao
        int int_codigo_ibge
        int pais_id FK
    }

    sg_cidades {
        int id PK
        string str_nome
        int estado_id FK
        int int_codigo_ibge
    }

    sg_bairros {
        int id PK
        string str_nome
        string str_importacao_id
        int cidade_id FK
        int int_localidade
    }

    sg_profissoes {
        int id PK
        string str_codigo
        string str_ocupacao
    }

    sg_pessoas ||--|| sg_enderecos : "pessoa_id (1:1)"
    sg_pessoas ||--o| sg_rgs : "pessoa_id (1:1)"
    sg_pessoas ||--o{ sg_telefones : "pessoa_id (1:N)"
    sg_tipos_telefones ||--o{ sg_telefones : "tipo"
    sg_necessidades_especiais ||--o{ sg_pessoas : "necessidade_especial_id"
    sa_etnias ||--o{ sg_pessoas : "etnia_id"
    sg_paises ||--o{ sg_estados : "pais_id"
    sg_estados ||--o{ sg_cidades : "estado_id"
    sg_cidades ||--o{ sg_bairros : "cidade_id"
    sg_estados ||--o{ sg_enderecos : "estado_id"
    sg_cidades ||--o{ sg_enderecos : "cidade_id"
    sg_bairros ||--o{ sg_enderecos : "bairro_id"
    sg_paises ||--o{ sg_pessoas : "nacionalidade_id"
    sg_cidades ||--o{ sg_pessoas : "naturalidade_id"
    sg_estados ||--o{ sg_pessoas : "uf_naturalidade_id"
    sg_profissoes ||--o{ sg_pessoas : "profissao_id"
    sg_estados ||--o{ sg_rgs : "estado_emissao_id"
```

---

## 2. Estrutura Academica (22 tabelas)

Unidades, cursos, periodos letivos, turmas, disciplinas, etapas e demais tabelas estruturais referenciadas pelo escopo do aluno.

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
        boolean bool_status
        int diretor_id FK
        int endereco_id FK
        int secretario_id FK
        int tipo_de_unidade_id FK
        int regiao_id FK
        boolean bool_mantenedora
    }

    sa_cursos {
        int id PK
        varchar str_nome
        varchar str_abreviatura
        int int_estrutura_curricular
        boolean bool_curso_aee
        int curso_sucessor_id FK
    }

    sa_periodos_letivos {
        int id PK
        varchar str_nome
        varchar str_ano
        boolean bool_padrao
        timestamp dt_inicial
        timestamp dt_final
        int secretario_de_educacao_id FK
    }

    sa_turmas {
        int id PK
        varchar str_nome
        boolean bool_ativo
        int int_total_vagas
        int unidade_id FK
        int curso_id FK
        int ano_de_escolaridade_id FK
        int turno_id FK
        int periodo_letivo_id FK
        int grade_curricular_id FK
        int horario_id FK
        int sala_id FK
        boolean bool_multiseriada
        boolean bool_extensao
    }

    sa_disciplinas {
        int id PK
        varchar str_nome
        varchar str_abreviatura
        int area_de_conhecimento_id FK
        smallint int_codigo_inep
    }

    sa_anos_de_escolaridade {
        int id PK
        varchar str_nome
        int int_numero_do_ano_de_escolaridade
        int curso_id FK
        int int_idade_de_referencia
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
        boolean bool_possui_recuperacao
        int tipo_de_etapa_id FK
    }

    sa_turnos {
        int id PK
        varchar str_nome
        boolean bool_integral
        int tipo_de_turno_id FK
    }

    sa_grades_curriculares {
        int id PK
        varchar str_nome
        int int_status
        int curso_id FK
        int periodo_letivo_id FK
    }

    sa_grades_curriculares_disciplinas {
        int grade_curricular_id PK "FK"
        int disciplina_id PK "FK"
        int ano_de_escolaridade_id PK "FK"
        boolean bool_apura_nota
        boolean bool_apura_falta
        int int_carga_horaria
        int arredondamento_id FK
        boolean bool_avaliar_em_conceito
    }

    sa_professores {
        int id PK
        int pessoa_id FK
        boolean bool_ativo
    }

    sa_aulas {
        int id PK
        varchar str_conteudo
        timestamp dt_aula
        int disciplina_id FK
        int turma_id FK
        int professor_id FK
        int horario_aula_id FK
        boolean bool_aula_dada
        boolean bool_eletiva
        int grupo_de_aula_id FK
    }

    sa_tipos_de_unidades {
        int id PK
        varchar str_nome
    }

    sa_regioes {
        int id PK
        varchar str_nome
        int periodo_letivo_id FK
        int curso_id FK
    }

    sa_salas {
        int id PK
        varchar str_nome
        int periodo_letivo_id FK
        int unidade_id FK
        int int_capacidade
        float float_comprimento
        float float_largura
        float float_metragem
        int int_pavimento
        boolean bool_possui_acessibilidade
        boolean bool_ativo
    }

    sa_horarios {
        int id PK
        varchar str_nome
        int turno_id FK
        int int_minutos_hora_aula
    }

    sa_areas_de_conhecimento {
        int id PK
        varchar str_nome
        varchar str_descricao
        int area_principal_id FK
    }

    sa_tipos_de_turnos {
        int id PK
        varchar str_nome
    }

    sa_tipos_de_etapas {
        int id PK
        varchar str_nome
        boolean bool_resultado_final
        boolean bool_recuperacao_final
    }

    sa_arredondamentos {
        int id PK
        varchar str_nome
    }

    sa_horarios_aulas {
        int id PK
        varchar str_hora_inicial
        varchar str_hora_final
        int int_dia_da_semana
        int horario_id FK
        int int_sequencial_aula
    }

    sa_grupos_de_aulas {
        int id PK
        timestamp dt_aula
        int turma_id FK
        int professor_id FK
        int horario_aula_id FK
        varchar str_conteudo
        varchar str_observacao
    }

    sa_cursos ||--o{ sa_anos_de_escolaridade : "anos do curso"
    sa_cursos ||--o{ sa_etapas : "etapas do curso"
    sa_cursos ||--o{ sa_grades_curriculares : "grades"
    sa_grades_curriculares ||--o{ sa_grades_curriculares_disciplinas : "disciplinas da grade"
    sa_disciplinas ||--o{ sa_grades_curriculares_disciplinas : "em grades"
    sa_unidades ||--o{ sa_turmas : "turmas da unidade"
    sa_anos_de_escolaridade ||--o{ sa_turmas : "ano da turma"
    sa_turnos ||--o{ sa_turmas : "turno"
    sa_turmas ||--o{ sa_aulas : "aulas da turma"
    sa_disciplinas ||--o{ sa_aulas : "disciplina da aula"
    sa_professores ||--o{ sa_aulas : "professor da aula"
    sa_tipos_de_unidades ||--o{ sa_unidades : "tipo_de_unidade_id"
    sa_regioes ||--o{ sa_unidades : "regiao_id"
    sa_salas ||--o{ sa_turmas : "sala_id"
    sa_horarios ||--o{ sa_turmas : "horario_id"
    sa_areas_de_conhecimento ||--o{ sa_disciplinas : "area_de_conhecimento_id"
    sa_tipos_de_turnos ||--o{ sa_turnos : "tipo_de_turno_id"
    sa_tipos_de_etapas ||--o{ sa_etapas : "tipo_de_etapa_id"
    sa_arredondamentos ||--o{ sa_grades_curriculares_disciplinas : "arredondamento_id"
    sa_horarios ||--o{ sa_horarios_aulas : "horario_id"
    sa_horarios_aulas ||--o{ sa_aulas : "horario_aula_id"
    sa_grupos_de_aulas ||--o{ sa_aulas : "grupo_de_aula_id"
    sa_periodos_letivos ||--o{ sa_turmas : "periodo_letivo_id"
    sa_grades_curriculares ||--o{ sa_turmas : "grade_curricular_id"
    sa_cursos ||--o{ sa_turmas : "curso_id"
    sa_periodos_letivos ||--o{ sa_etapas : "periodo_letivo_id"
    sa_periodos_letivos ||--o{ sa_grades_curriculares : "periodo_letivo_id"
    sa_anos_de_escolaridade ||--o{ sa_grades_curriculares_disciplinas : "ano_de_escolaridade_id"
```

---

## 3. Aluno (12 tabelas)

Cadastro do aluno, deficiencias, documentacao e carteirinha.

```mermaid
erDiagram
    sa_alunos {
        int id PK
        varchar str_matricula
        varchar str_id_aluno_inep
        int int_situacao
        timestamp dt_matricula_na_rede
        int pessoa_id FK
        boolean bool_possui_deficiencia
        boolean bool_auxilio_brasil
        varchar str_url_foto
    }

    sa_status_alunos {
        int id
        varchar str_nome
        varchar str_cor
    }

    sa_fichas_de_matricula {
        int id PK
        int aluno_id FK
        int unidade_id FK
        timestamp dt_matricula_na_unidade
    }

    sa_historico_de_nomes_civis {
        int id PK
        text str_nome_novo
        text str_nome_antigo
        int pessoa_id FK
        int usuario_alteracao_id FK
    }

    sa_deficiencias_alunos {
        int id PK
        int cid_id FK
        int aluno_id FK
        boolean bool_possui_laudo_medico
    }

    sa_laudos_deficiencias {
        int id PK
        int deficiencia_aluno_id FK
        text str_nome
        text str_path
        text str_url
    }

    sa_carteiras_de_estudante {
        int id PK
        int aluno_id FK
        int enturmacao_id FK
        int layout_carteirinha_id FK
        date dt_validade
    }

    sa_irmaos {
        int id PK
        int aluno_um_id FK
        int aluno_dois_id FK
    }

    sg_cids {
        int id PK
        varchar str_codigo
        varchar str_descricao
        varchar str_codigo_cid_11
    }

    sa_layouts_carteirinhas {
        int id PK
        varchar str_nome
        boolean bool_ativo
        int int_prazo_validade
        int int_tipo_de_papel
        boolean bool_padrao
        int int_mes_validade
        int int_ano_validade
    }

    sg_pessoas ||--|| sa_alunos : "pessoa_id"
    sa_alunos ||--o{ sa_deficiencias_alunos : "deficiencias"
    sa_deficiencias_alunos ||--o{ sa_laudos_deficiencias : "laudos"
    sa_alunos ||--o{ sa_carteiras_de_estudante : "carteiras"
    sa_alunos ||--o{ sa_fichas_de_matricula : "fichas matricula"
    sa_alunos ||--o{ sa_irmaos : "irmaos"
    sg_cids ||--o{ sa_deficiencias_alunos : "cid_id"
    sa_layouts_carteirinhas ||--o{ sa_carteiras_de_estudante : "layout_carteirinha_id"
```

Tabela auxiliar: `sa_agendamento_de_atendimento_especializado` (aluno_id FK).

---

## 4. Responsaveis (5 tabelas)

Responsaveis legais, academicos e responsaveis pela saida do aluno.

```mermaid
erDiagram
    sa_responsaveis {
        int id PK
        int pessoa_id FK
    }

    sa_tipos_de_responsavel {
        int id PK
        varchar str_nome
        boolean bool_filiacao
    }

    sa_responsaveis_por_alunos {
        int id PK
        varchar str_nome_responsavel
        int aluno_id FK
        int responsavel_id FK
        int tipo_de_responsavel_id FK
        boolean bool_responsavel_legal
        boolean bool_responsavel_academico
        boolean bool_responsavel_reside_com_aluno
        boolean bool_falecido
        boolean bool_nao_declarado
        boolean bool_responsavel_pela_matricula
        boolean bool_possui_laudo_da_guarda
    }

    sa_alunos_responsaveis_historico {
        int id PK
        int aluno_id FK
        int responsavel_id FK
        int usuario_insercao_id FK
        int usuario_remocao_id FK
        varchar str_tipo_acao
    }

    sa_responsaveis_por_saida_dos_alunos {
        int id PK
        varchar str_nome
        date dt_de_autorizacao
        int int_status
        date dt_de_cancelamento
        text str_motivo
        int enturmacao_id FK
        int usuario_autorizacao_id FK
        int usuario_cancelamento_id FK
    }

    sg_pessoas ||--|| sa_responsaveis : "pessoa_id"
    sa_alunos ||--o{ sa_responsaveis_por_alunos : "aluno"
    sa_responsaveis ||--o{ sa_responsaveis_por_alunos : "responsavel"
    sa_tipos_de_responsavel ||--o{ sa_responsaveis_por_alunos : "tipo"
    sa_alunos ||--o{ sa_alunos_responsaveis_historico : "historico"
    sa_enturmacoes ||--o{ sa_responsaveis_por_saida_dos_alunos : "enturmacao"
```

---

## 5. Enturmacao (5 tabelas)

Vinculo do aluno a turma, curso e periodo letivo. Tabela central que conecta o aluno ao contexto academico.

```mermaid
erDiagram
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
        boolean bool_enturmacao_integral
        int usuario_remocao_id FK
        int monitor_id FK
    }

    sa_status_enturmacoes {
        int id
        varchar str_nome
        varchar str_cor
    }

    sa_enturmacoes_tipos_de_documento {
        int enturmacao_id PK "FK"
        int tipo_de_documento_id PK "FK"
    }

    sa_registros_rematricula {
        int id PK
        int enturmacao_id FK
        int periodo_letivo_id FK
        int int_situacao
        datetime dt_situacao
        int usuario_responsavel_id FK
        int usuario_confirmacao_id FK
        boolean bool_rematricula_online
    }

    sa_tipos_de_documento {
        int id PK
        varchar str_nome
        boolean bool_sexo_masculino
        boolean bool_sexo_feminino
        int int_idade_minima
    }

    sa_alunos ||--o{ sa_enturmacoes : "aluno_id"
    sa_enturmacoes ||--o{ sa_enturmacoes_tipos_de_documento : "documentos"
    sa_enturmacoes ||--o{ sa_registros_rematricula : "rematricula"
    sa_tipos_de_documento ||--o{ sa_enturmacoes_tipos_de_documento : "tipo_de_documento_id"
```

Tabela auxiliar: `sa_respostas_fichas_de_acompanhamento` (enturmacao_id FK).

---

## 6. Notas e Avaliacoes (12 tabelas)

Fichas de disciplina, avaliacoes, notas e conselho de classe — todos vinculados a enturmacao.

```mermaid
erDiagram
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

    sa_avaliacoes {
        int id PK
        varchar str_nome
        float float_valor
        int int_tipo_avaliacao
        timestamp dt_avaliacao
        boolean bool_recuperacao
        int disciplina_id FK
        int turma_id FK
        int etapa_id FK
        int curso_id FK
        int grupo_de_avaliacao_id FK
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

    sa_registros_conselho_de_classe {
        int id PK
        float float_nota
        int int_faltas
        varchar str_parecer
        int disciplina_id FK
        int etapa_id FK
        int enturmacao_id FK
    }

    sa_notas_oriundas_de_transferencia {
        int id PK
        int enturmacao_id FK
        int disciplina_id FK
        int etapa_id FK
        int int_valor_etapa_origem
        float float_nota_origem
        int int_faltas
        boolean bool_nota_parcial
        float float_nota_convertida
    }

    sa_notas_pos_transferencia {
        int id PK
        float float_nota
        int avaliacao_id FK
        int disciplina_id FK
        int etapa_id FK
        int enturmacao_id FK
    }

    sa_notas_e_faltas_transferencias_externas {
        int id PK
        int int_faltas
        float float_nota
        int etapa_id FK
        int transferencia_externa_id FK
        int ficha_de_disciplina_id FK
        int int_valor_etapa_origem
        float float_nota_origem
        boolean bool_nota_parcial
    }

    sa_notas_avancos {
        int id PK
        int disciplina_id FK
        int avanco_id FK
        float float_nota
    }

    sa_faltas_avulsas_ata_de_resultado_final {
        int id PK
        int enturmacao_id FK
        int int_faltas
    }

    sa_enturmacoes ||--o{ sa_fichas_de_disciplinas : "fichas do aluno"
    sa_fichas_de_disciplinas ||--o{ sa_fichas_de_disciplinas_etapas : "notas por etapa"
    sa_fichas_de_disciplinas ||--o{ sa_notas_avaliacoes : "notas"
    sa_avaliacoes ||--o{ sa_notas_avaliacoes : "notas da avaliacao"
    sa_grupos_de_avaliacoes ||--o{ sa_avaliacoes : "grupo"
    sa_enturmacoes ||--o{ sa_registros_conselho_de_classe : "conselho"
    sa_enturmacoes ||--o{ sa_notas_oriundas_de_transferencia : "notas transferencia"
    sa_enturmacoes ||--o{ sa_notas_pos_transferencia : "notas pos transferencia"
    sa_enturmacoes ||--o{ sa_faltas_avulsas_ata_de_resultado_final : "faltas avulsas"
```

Tabela auxiliar: `sa_solicitacoes_de_calculo_de_notas`.

---

## 7. Frequencia e Faltas (5 tabelas)

Registro de presencas vinculadas a aula e faltas diarias independentes de aula.

```mermaid
erDiagram
    sa_frequencias {
        int id PK
        boolean bool_ausente
        int aula_id FK
        int enturmacao_id FK
        int professor_id FK
        int turma_id FK
        int disciplina_id FK
        int tipo_de_justificativa_de_falta_id FK
        int justificativa_de_falta_id FK
        boolean bool_criado_por_justificativa
    }

    sa_faltas_diarias {
        int id PK
        date dt_falta
        int enturmacao_id FK
        int turma_id FK
        int professor_id FK
        boolean bool_ausente
        int tipo_de_justificativa_de_falta_id FK
        int justificativa_de_falta_id FK
    }

    sa_justificativas_de_faltas {
        int id PK
        int enturmacao_id FK
        int tipo_de_justificativa_de_falta_id FK
        datetime dt_inicial
        datetime dt_final
        text str_justificativa
        varchar str_path_anexo
        varchar str_url_anexo
        int frequencia_id FK
        int etapa_de_abono_id FK
        boolean bool_falta_abonada
    }

    sa_tipos_de_justificativas_de_faltas {
        int id PK
        varchar str_nome
        boolean bool_inativo
    }

    sa_enturmacoes ||--o{ sa_frequencias : "frequencia do aluno"
    sa_enturmacoes ||--o{ sa_faltas_diarias : "faltas diarias"
    sa_enturmacoes ||--o{ sa_justificativas_de_faltas : "justificativas"
    sa_tipos_de_justificativas_de_faltas ||--o{ sa_frequencias : "tipo justificativa"
    sa_tipos_de_justificativas_de_faltas ||--o{ sa_faltas_diarias : "tipo justificativa"
    sa_tipos_de_justificativas_de_faltas ||--o{ sa_justificativas_de_faltas : "tipo justificativa"
    sa_justificativas_de_faltas ||--o{ sa_frequencias : "justificativa"
    sa_justificativas_de_faltas ||--o{ sa_faltas_diarias : "justificativa"
```

---

## 8. Movimentacao do Aluno (10 tabelas)

Transferencias, cancelamentos, desistencias, evasoes, avancos e encaminhamentos — todos vinculados a enturmacao.

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
        boolean bool_aceite_unidade_origem
        boolean bool_aceite_unidade_destino
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
        int periodo_letivo_id FK
        int unidade_id FK
        int curso_id FK
    }

    sa_cancelamentos {
        int id PK
        int enturmacao_id FK
        timestamp dt_saida
        datetime dt_retorno
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
        datetime dt_avanco
    }

    sa_encaminhamentos_entre_periodos_letivos {
        int id PK
        int int_situacao
        int enturmacao_origem_id FK
        int enturmacao_destino_id FK
        int periodo_letivo_destino_id FK
        int ano_de_escolaridade_destino_id FK
        int unidade_destino_id FK
        int usuario_solicitante_id FK
        int usuario_receptor_id FK
    }

    sa_ocorrencias {
        int id PK
        int enturmacao_id FK
        date dt_ocorrencia
        int tipo_de_ocorrencia_id FK
        varchar str_observacao
        varchar str_orientacao
        boolean bool_visivel_ao_aluno_responsavel
        int disciplina_id FK
        int etapa_id FK
        int usuario_id FK
    }

    sa_tipos_de_ocorrencias {
        int id PK
        varchar str_nome
        int int_categoria
        boolean bool_inativo
    }

    sa_enturmacoes ||--o{ sa_pedidos_de_transferencia : "origem/destino"
    sa_enturmacoes ||--o{ sa_transferencias_externas : "transferencia"
    sa_enturmacoes ||--o{ sa_transferencias_entre_cursos : "origem/destino"
    sa_enturmacoes ||--o{ sa_cancelamentos : "cancelamento"
    sa_enturmacoes ||--o{ sa_desistencias : "desistencia"
    sa_enturmacoes ||--o{ sa_evasoes : "evasao"
    sa_enturmacoes ||--o{ sa_avancos : "avanco"
    sa_enturmacoes ||--o{ sa_encaminhamentos_entre_periodos_letivos : "encaminhamento"
    sa_enturmacoes ||--o{ sa_ocorrencias : "ocorrencia"
    sa_tipos_de_ocorrencias ||--o{ sa_ocorrencias : "tipo_de_ocorrencia_id"
```

Tabelas complementares: `sa_historico_remanejamentos`, `sa_pedidos_de_transferencia_responsavel`, `sa_status_pedidos_de_transferencia_responsavel`, `sa_anexos_do_avanco`, `sa_regras_de_encaminhamento`, `sa_regras_de_encaminhamento_unidade`, `sa_regras_de_encaminhamento_necessidade_especial`.

---

## 9. Historico Escolar (5 tabelas)

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
        text str_obrservacoes
    }

    sa_historicos_escolares_anos_de_escolaridade {
        int id PK
        varchar str_ano_de_escolaridade
        varchar str_turma
        varchar str_turno
        varchar str_ano_letivo
        varchar str_estabelecimento
        varchar str_municipio
        varchar str_resultado_final
        int int_carga_horaria_total
        int int_total_aulas_dadas
        int int_total_faltas
        int int_dias_letivos
        float float_percentual_de_faltas
        int historico_escolar_id FK
        int enturmacao_id FK
        int grade_curricular_id FK
        int ano_de_escolaridade_id FK
    }

    sa_historicos_escolares_disciplinas {
        int id PK
        varchar str_disciplina
        int int_faltas
        int int_aulas_dadas
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

    sa_alunos ||--o{ sa_historicos_escolares : "historicos"
    sa_historicos_escolares ||--o{ sa_historicos_escolares_anos_de_escolaridade : "anos"
    sa_historicos_escolares_anos_de_escolaridade ||--o{ sa_historicos_escolares_disciplinas : "disciplinas"
```

---

## 10. Ambiente Virtual - Dados do Aluno (5 tabelas)

Progresso, respostas e acessos do aluno no AVA — todos vinculados a enturmacao.

```mermaid
erDiagram
    sa_progressos_atividades_online {
        int id PK
        int atividade_online_id FK
        int enturmacao_id FK
        int int_situacao
        boolean bool_corrigida
        float float_nota_obtida
        datetime dt_inicio_execucao_atividade_online
    }

    sa_respostas_atividades_online {
        int id PK
        text str_resposta
        float float_nota
        varchar str_observacao
        int pergunta_atividade_online_id FK
        int enturmacao_id FK
        int professor_id FK
        int opcao_de_resposta_id FK
        varchar str_path_anexo
        boolean bool_respondido_pelo_professor
    }

    sa_acessos_aulas_online {
        int id PK
        int aula_online_id FK
        int enturmacao_id FK
    }

    sa_controle_de_tempo_atividades_online {
        int id PK
        int atividade_online_id FK
        int enturmacao_id FK
    }

    sa_controle_de_tempo_aulas_online {
        int id PK
        int aula_online_id FK
        int enturmacao_id FK
    }

    sa_enturmacoes ||--o{ sa_progressos_atividades_online : "progressos"
    sa_enturmacoes ||--o{ sa_respostas_atividades_online : "respostas"
    sa_enturmacoes ||--o{ sa_acessos_aulas_online : "acessos"
    sa_enturmacoes ||--o{ sa_controle_de_tempo_atividades_online : "tempo atividade"
    sa_enturmacoes ||--o{ sa_controle_de_tempo_aulas_online : "tempo aula"
```

---

## 11. Quesitos Descritivos e Gamificacao (3 tabelas)

Respostas de avaliacao descritiva e conquistas de gamificacao — vinculados a enturmacao.

```mermaid
erDiagram
    sa_respostas_quesitos {
        int id PK
        text str_resposta
        text str_sigla
        int enturmacao_id FK
        int quesito_id FK
        int etapa_id FK
        int disciplina_id FK
    }

    sa_conquistas_gamificacao_do_aluno {
        int id PK
        int enturmacao_id FK
        int disciplina_id FK
        int etapa_id FK
        int faixa_de_gamificacao_do_aluno_id FK
    }

    sa_respostas_conceitos_conselho_de_classe {
        int id PK
        int conceito_conselho_de_classe_id FK
        int enturmacao_id FK
        int etapa_id FK
    }

    sa_enturmacoes ||--o{ sa_respostas_quesitos : "respostas"
    sa_enturmacoes ||--o{ sa_conquistas_gamificacao_do_aluno : "conquistas"
    sa_enturmacoes ||--o{ sa_respostas_conceitos_conselho_de_classe : "conceitos conselho"
```

Tabela auxiliar: `sa_respostas_quesitos_por_turma` (turma_id FK, etapa_id FK).

---

## 12. Avaliacoes Especiais do Aluno (3 tabelas)

Anamnese, PEI e atendimento especializado — vinculados diretamente a aluno_id.

```mermaid
erDiagram
    sa_avaliacoes_anamnese {
        int id PK
        int anamnese_id FK
        int aluno_id FK
        int periodo_letivo_id FK
    }

    sa_avaliacoes_pei {
        int id PK
        int pei_id FK
        int aluno_id FK
        int periodo_letivo_id FK
    }

    sa_agendamento_de_atendimento_especializado {
        int id PK
        int periodo_letivo_id FK
        int unidade_id FK
        int turma_id FK
        int aluno_id FK
        varchar str_assunto
        text str_justificativa
        datetime dt_do_atendimento
    }

    sa_alunos ||--o{ sa_avaliacoes_anamnese : "anamnese"
    sa_alunos ||--o{ sa_avaliacoes_pei : "pei"
    sa_alunos ||--o{ sa_agendamento_de_atendimento_especializado : "atendimento"
```

Tabela auxiliar: `sa_avaliacoes_anamnese_dos_alunos` (aluno_id FK).

---

## 13. Enums do Modulo Academico

Valores possiveis para colunas `int_situacao`, `int_status`, `int_tipo_*` e similares. Fonte: `app/Extras/Enums/Academico/`.

### Aluno e Enturmacao

| Enum | Coluna | Valores |
|---|---|---|
| **StatusAlunoEnum** | `sa_alunos.int_situacao` | 1=Regular, 2=Falecido |
| **StatusEnturmacaoEnum** | `sa_enturmacoes.int_situacao` | 1=Regular, 2=Pendente, 3=Transferido fora da rede, 4=Transferido dentro da rede, 5=Remanejado, 6=Cancelado, 7=Desistente, 8=Falecido, 9=Aprovado, 10=Reprovado, 11=Reprovado por nota, 12=Reprovado por falta, 13=Avancado, 14=Reclassificado, 15=Evadido, 16=Transferido entre cursos, 17=Transferencia pendente |
| **SituacaoRegistroRematriculaEnum** | `sa_registros_rematricula.int_situacao` | 1=Pendente, 2=Em validacao da unidade, 3=Aceito, 4=Rejeitado |

### Notas e Avaliacoes

| Enum | Coluna | Valores |
|---|---|---|
| **TipoDeAvaliacaoEnum** | `sa_avaliacoes.int_tipo_avaliacao` | 1=Atividade, 2=Prova |
| **SituacaoSolicitacaoDeCalculoDeNotasEnum** | `sa_solicitacoes_de_calculo_de_notas.int_situacao` | 1=Aguardando processamento, 2=Processando, 3=Erro ao processar, 4=Concluido |
| **TipoDeRespostaConceitoConselhoDeClasseEnum** | `sa_conceitos_conselho_de_classe.int_tipo_resposta` | 1=Texto, 2=Checkbox |

### Frequencia e Faltas

> Colunas `bool_ausente` em `sa_frequencias` e `sa_faltas_diarias` sao booleanas (true=falta, false=presenca), nao enums.

### Movimentacao

| Enum | Coluna | Valores |
|---|---|---|
| **StatusPedidoDeTransferenciaEnum** | `sa_pedidos_de_transferencia.int_status` | 1=Pendente, 2=Concluido, 3=Cancelado, 4=Rejeitado |
| **SolicitantePedidoDeTransferenciaEnum** | `sa_pedidos_de_transferencia.int_solicitante` | 1=Unidade origem, 2=Unidade destino |
| **StatusPedidoDeTransferenciaResponsavelEnum** | `sa_pedidos_de_transferencia_responsavel.int_status` | 1=Aguardando convocacao, 2=Convocado, 3=Concluido, 4=Cancelado, 5=Rejeitado |
| **TipoDeAvancoEnum** | `sa_avancos.int_tipo_avanco` | 1=Avanco, 2=Reclassificacao |
| **SituacaoEncaminhamentoEntrePeriodoLetivoEnum** | `sa_encaminhamentos_entre_periodos_letivos.int_situacao` | 1=Pendente, 2=Aceito, 3=Rejeitado |
| **CategoriaDaOcorrenciaEnum** | `sa_ocorrencias.int_categoria` | 1=Comportamental, 2=Pedagogica |
| **StatusAutorizacaoDeSaidaDoAluno** | `sa_responsaveis_por_saida_dos_alunos.int_status` | 1=Autorizado, 2=Cancelado |

### Historico Escolar

| Enum | Coluna | Valores |
|---|---|---|
| **TipoDeSituacaoAcademicaDoHistoricoEnum** | `sa_historicos_escolares` | 1=Aprovacao, 2=Reprovacao, 3=Evasao, 4=Desistencia, 5=Avanco, 6=Reclassificacao |

### Ambiente Virtual (AVA)

| Enum | Coluna | Valores |
|---|---|---|
| **ProgressoAtividadeOnlineEnum** | `sa_progressos_atividades_online.int_situacao` | 1=Nao iniciada, 2=Em andamento, 3=Finalizada, 4=Nao respondido no prazo |

### Estrutura Academica

| Enum | Coluna | Valores |
|---|---|---|
| **StatusGradeCurricularEnum** | `sa_grades_curriculares.int_status` | 1=Ativo, 2=Inativo |
| **TipoDaEtapaEnum** | `sa_etapas.int_tipo_da_etapa` | 1=Regular, 2=Recuperacao final, 3=Resultado final |
| **EstruturaCurricularEnum** | `sa_cursos.int_estrutura_curricular` | 1=Formacao geral basica, 2=Itinerario informativo, 3=Nao se aplica |
| **ModalidadeDeEnsinoEnum** | `sa_cursos.int_modalidade_de_ensino` | 1=Ensino regular, 2=Educacao especial, 3=EJA, 4=Educacao profissional |
| **MetodologiaDeEnsinoEnum** | `sa_cursos.int_metodologia_de_ensino` | 1=Presencial, 2=Semipresencial, 3=EAD |
| **FormaDeOrganizacaoDaTurmaEnum** | `sa_turmas.int_forma_de_organizacao` | 1=Serie/Ano, 2=Periodo semestral, 3=Ciclo, 4=Grupo nao seriado, 5=Modulo |
| **TipoDeAtendimentoEnsinoEnum** | `sa_turmas.int_tipo_de_atendimento_ensino` | 1=Escolarizacao, 2=AEE, 3=Atividade complementar |
| **DiaDaSemanaEnum** | `sa_horarios_aulas.int_dia_da_semana` | 1=Segunda, 2=Terca, 3=Quarta, 4=Quinta, 5=Sexta, 6=Sabado, 7=Domingo |

### Quesitos e Campos Personalizados

| Enum | Coluna | Valores |
|---|---|---|
| **QuesitoEnum** | `sa_quesitos.int_tipo_resposta` | 1=Texto, 2=Selecao, 3=Imagem |
| **CampoPersonalizadoEnum** | `sa_campos_personalizados.int_tipo_resposta` | 1=Texto, 2=Selecao |

---

## Resumo

| # | Grupo | Tabelas | Vinculo |
|---|---|---|---|
| 1 | Pessoa e Endereco | 12 | sg_pessoas.id (base) |
| 2 | Estrutura Academica | 22 | Unidades, cursos, turmas, disciplinas, etapas |
| 3 | Aluno | 12 | sa_alunos.pessoa_id → sg_pessoas |
| 4 | Responsaveis | 5 | sa_alunos.id |
| 5 | Enturmacao | 5 | sa_alunos.id → sa_enturmacoes |
| 6 | Notas e Avaliacoes | 12 | sa_enturmacoes.id |
| 7 | Frequencia e Faltas | 5 | sa_enturmacoes.id |
| 8 | Movimentacao | 10 | sa_enturmacoes.id |
| 9 | Historico Escolar | 5 | sa_alunos.id |
| 10 | AVA - Dados do Aluno | 5 | sa_enturmacoes.id |
| 11 | Quesitos e Gamificacao | 3 | sa_enturmacoes.id |
| 12 | Avaliacoes Especiais | 3 | sa_alunos.id |
| 13 | Enums | — | Referencia de valores inteiros |
| | **TOTAL** | **99** | |
