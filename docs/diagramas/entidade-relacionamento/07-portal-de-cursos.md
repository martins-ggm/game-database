# ER - Modulo Portal de Cursos (Prefixo: pc_*)

52 tabelas (34 presenciais + 15 online + 3 credenciamento). Modulo de gestao de cursos e capacitacoes: cadastro de cursos, turmas, inscricoes, aulas, frequencia, certificados, campos personalizados, filtros de inscricao integrados ao Academico, recursos/emprestimos, atividades complementares, questionarios, **trilhas de aprendizagem, cursos online com modulos/aulas/questionarios, progresso e favoritos**, **credenciamento com controle de acesso, layout de cracha e campos variaveis**.

> **Fluxo principal:** Cursos (`pc_cursos`) possuem Turmas (`pc_turmas`), que recebem Inscricoes (`pc_inscricoes`). Turmas tem Aulas (`pc_aulas`) com controle de Presenca (`pc_presencas`). Ao final, Certificados (`pc_certificados`) sao gerados. Filtros (`pc_filtros`) permitem restringir inscricoes por periodo letivo, unidade, disciplina e ano de escolaridade do modulo Academico.

## 1. Cursos e Cadastros Base

```mermaid
erDiagram
    pc_cursos {
        int id PK
        text str_nome
        text str_descricao
        text str_instituicao_provedora
        int int_carga_horaria
        text str_conteudo_ministrado
        boolean bool_apurar_frequencia
        int int_media_aprovacao
        boolean bool_bloquear_inscritos_em_outro_curso
        boolean bool_enviar_sms_ao_se_inscrever
        text str_remetente_sms
        text str_mensagem_sms
        int questionario_id FK
        int responsavel_id FK
        int pre_requisito_id FK "auto-referencia"
        text str_certificado_nome_responsavel
        text str_certificado_cargo_responsavel
        text str_certificado_decreto
        text str_certificado_setor
        text str_certificado_cidade
        boolean bool_habilitar_cadastro_reserva
        text str_remetente_sms_cadastro_reserva
        text str_mensagem_sms_cadastro_reserva
        int int_prazo_espera_cadastro_reserva
        boolean bool_enviar_sms_alarme_ausencia
        text str_remetente_sms_alarme_ausencia
        text str_mensagem_sms_alarme_ausencia
        boolean bool_obrigatorio_responder_questionario
        text str_apelido
        boolean bool_exigir_nome_dt_nasc
        boolean bool_validar_inscricao
        boolean bool_enviar_email_ao_se_inscrever
        text str_path_imagem
        boolean bool_exibir_curso_na_home
        boolean bool_gerar_certificado
        boolean bool_permitir_inscricao_em_mais_de_uma_turma
        int int_carga_horaria_palestrante
        int tipo_de_formacao_id FK
        text str_url_imagem
        int modelo_de_certificado_participante_id FK
        int modelo_de_certificado_palestrante_id FK
        int int_modalidade "NOVO - 1=Presencial 2=Online null=Presencial"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_status {
        int id PK "nao auto-increment"
        text str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_turnos {
        int id PK
        text str_nome
        text str_importacao_id
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_questionarios {
        int id PK
        text str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_perguntas {
        int id PK
        text str_descricao
        int questionario_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_modelos_de_certificados {
        int id PK
        text str_nome
        text str_path_imagem_frente
        text str_path_imagem_verso
        boolean bool_padrao
        text str_url_imagem_frente
        text str_url_imagem_verso
        text str_conteudo
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_configuracoes {
        int id PK
        int perfil_usuario_id FK
        int int_quantidade_registros_por_pagina
        boolean bool_exibir_tawkto
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_cursos ||--o{ pc_cursos : "pre_requisito (auto-referencia)"
    pc_cursos }o--o| pc_questionarios : "questionario_id"
    pc_cursos }o--|| sg_usuarios : "responsavel_id"
    pc_cursos }o--o| sg_tipos_de_formacoes : "tipo_de_formacao_id"
    pc_cursos }o--o| pc_modelos_de_certificados : "modelo_certificado_participante"
    pc_cursos }o--o| pc_modelos_de_certificados : "modelo_certificado_palestrante"
    pc_questionarios ||--o{ pc_perguntas : "questionario tem perguntas"
    pc_configuracoes }o--|| sg_perfis : "perfil_usuario_id"
```

## 2. Turmas e Inscricoes

```mermaid
erDiagram
    pc_turmas {
        int id PK
        text str_nome
        text str_palestrante
        datetime dt_inicio
        datetime dt_termino
        datetime dt_inicio_inscricoes
        datetime dt_termino_inscricoes
        int int_qtd_total_vagas
        int int_qtd_vagas_disponiveis
        int curso_id FK
        int turno_id FK
        time hr_inicio
        time hr_termino
        boolean bool_vagas_ilimitadas
        boolean bool_habilitar_cadastro_reserva
        boolean bool_enviar_sms_cadastro_reserva
        int int_prazo_espera_cadastro_reserva
        text str_remetente_sms_cadastro_reserva
        text str_mensagem_sms_cadastro_reserva
        boolean bool_inscricao_por_cpf_autorizado
        boolean bool_enviar_email_alarme_ausencia
        boolean bool_enviar_sms_alarme_ausencia
        text str_remetente_sms_alarme_ausencia
        text str_mensagem_sms_alarme_ausencia
        int ficha_de_inscricao_personalizada_id FK
        boolean bool_carga_horaria_por_aula
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_inscricoes {
        int id PK
        text str_protocolo
        int usuario_id FK
        int curso_id FK
        int filtro_id FK
        boolean bool_questionario_respondido
        int status_id FK
        int turma_id FK
        int turno_id FK
        int inscricao_trilha_id FK "NOVO - vinculo com trilha"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_palestrantes {
        int id PK
        int usuario_id FK
        int turma_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_cpfs_autorizados {
        int id PK
        text str_cpf
        int turma_id FK
        text str_importacao_id
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_cursos ||--o{ pc_turmas : "curso tem turmas"
    pc_turnos ||--o{ pc_turmas : "turno tem turmas"
    pc_turmas ||--o{ pc_inscricoes : "turma tem inscricoes"
    pc_turmas ||--o{ pc_palestrantes : "turma tem palestrantes"
    pc_turmas ||--o{ pc_cpfs_autorizados : "turma tem cpfs autorizados"
    pc_turmas }o--o| pc_fichas_de_inscricoes_personalizadas : "ficha_inscricao_id"
    pc_cursos ||--o{ pc_inscricoes : "curso tem inscricoes"
    pc_inscricoes }o--|| sg_usuarios : "usuario_id"
    pc_inscricoes }o--o| pc_filtros : "filtro_id"
    pc_inscricoes }o--|| pc_status : "status_id"
    pc_inscricoes }o--o| pc_turnos : "turno_id"
    pc_palestrantes }o--|| sg_usuarios : "usuario_id"
```

## 3. Aulas e Frequencia

```mermaid
erDiagram
    pc_aulas {
        int id PK
        text str_nome
        time hr_inicio
        time hr_termino
        datetime dt_aula
        int turma_id FK
        int int_tipo_aula
        text str_endereco_aula
        boolean bool_enviar_notificacao_email
        int int_horas_notificacao_email
        boolean bool_aula_notificada
        text str_conteudo_programado
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_presencas {
        int aula_id PK "PK composta"
        int inscricao_id PK "PK composta"
        boolean bool_falta
        text str_token_presenca
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_tokens_frequencias_aulas {
        int id PK
        text str_token
        int int_tempo_duracao
        datetime dt_inicio_validade
        datetime dt_termino_validade
        int aula_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_videos_aula {
        int id PK
        int aula_id FK
        int int_ordem
        text str_nome
        text str_link
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_anexos_aula {
        int id PK
        text str_nome
        text str_url
        text str_path
        int aula_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_turmas ||--o{ pc_aulas : "turma tem aulas"
    pc_aulas ||--o{ pc_presencas : "aula tem presencas"
    pc_aulas ||--o{ pc_tokens_frequencias_aulas : "aula tem tokens"
    pc_aulas ||--o{ pc_videos_aula : "aula tem videos"
    pc_aulas ||--o{ pc_anexos_aula : "aula tem anexos"
    pc_inscricoes ||--o{ pc_presencas : "inscricao tem presencas"
```

## 4. Certificados e Anexos

```mermaid
erDiagram
    pc_certificados {
        int id PK
        int inscricao_id FK
        text str_nome_aluno
        text str_local
        text str_nome_curso
        text str_periodo_curso
        text str_certificado_nome_responsavel
        text str_certificado_cargo_responsavel
        text str_certificado_decreto
        int int_carga_horaria
        text str_conteudo_ministrado
        text str_protocolo
        text str_nome_cliente
        text str_orgao_cliente
        text str_instituicao_provedora
        int int_status
        text str_url_assinatura_responsavel
        text str_nome_palestrante
        int usuario_id FK
        int turma_id FK
        int curso_id FK
        datetime dt_geracao
        text str_conteudo_frente
        int int_carga_horaria_cumprida
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_anexos {
        int id PK
        int curso_id FK
        text str_descricao
        text str_nome_arquivo
        text str_path_arquivo
        text str_url_arquivo
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_inscricoes ||--o{ pc_certificados : "inscricao gera certificado"
    pc_certificados }o--|| sg_usuarios : "usuario_id"
    pc_certificados }o--|| pc_turmas : "turma_id"
    pc_certificados }o--|| pc_cursos : "curso_id"
    pc_cursos ||--o{ pc_anexos : "curso tem anexos"
```

## 5. Campos Personalizados

```mermaid
erDiagram
    pc_fichas_de_inscricoes_personalizadas {
        int id PK
        text str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_secoes_campos_personalizados {
        int id PK
        text str_nome
        int int_modelo
        int modelo_id
        int int_ordem
        int ficha_de_inscricao_personalizada_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_campos_personalizados {
        int id PK
        text str_pergunta
        boolean bool_obrigatorio
        int int_tipo_resposta
        text array_opcoes_de_resposta
        text str_mascara
        int secao_campo_personalizado_id FK
        int int_posicao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_respostas_campos_personalizados {
        int id PK
        text str_resposta
        int modelo_id
        int campo_personalizado_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_fichas_de_inscricoes_personalizadas ||--o{ pc_secoes_campos_personalizados : "ficha tem secoes"
    pc_secoes_campos_personalizados ||--o{ pc_campos_personalizados : "secao tem campos"
    pc_campos_personalizados ||--o{ pc_respostas_campos_personalizados : "campo tem respostas"
```

## 6. Filtros de Inscricao (Integrados ao Academico)

```mermaid
erDiagram
    pc_filtros {
        int id PK
        text str_nome
        int int_qtd_total_vagas
        int int_qtd_vagas_disponiveis
        int turma_id FK
        text array_funcoes
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_filtros_periodos_letivos {
        int periodo_letivo_id PK "PK composta"
        int filtro_id PK "PK composta"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_filtros_unidades {
        int unidade_id PK "PK composta"
        int filtro_id PK "PK composta"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_filtros_disciplinas {
        int disciplina_id PK "PK composta"
        int filtro_id PK "PK composta"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_filtros_anos_de_escolaridade {
        int ano_de_escolaridade_id PK "PK composta"
        int filtro_id PK "PK composta"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_turmas ||--o{ pc_filtros : "turma tem filtros"
    pc_filtros ||--o{ pc_filtros_periodos_letivos : "filtro tem periodos letivos"
    pc_filtros ||--o{ pc_filtros_unidades : "filtro tem unidades"
    pc_filtros ||--o{ pc_filtros_disciplinas : "filtro tem disciplinas"
    pc_filtros ||--o{ pc_filtros_anos_de_escolaridade : "filtro tem anos de escolaridade"
    pc_filtros_periodos_letivos }o--|| sa_periodos_letivos : "periodo_letivo_id"
    pc_filtros_unidades }o--|| sa_unidades : "unidade_id"
    pc_filtros_disciplinas }o--|| sa_disciplinas : "disciplina_id"
    pc_filtros_anos_de_escolaridade }o--|| sa_anos_de_escolaridade : "ano_de_escolaridade_id"
```

## 7. Recursos, Emprestimos e Atividades

```mermaid
erDiagram
    pc_recursos {
        int id PK
        text str_nome
        text str_patrimonio
        boolean bool_devolve
        boolean bool_emprestado
        boolean bool_previsao_devolucao
        boolean bool_empresta
        text str_importacao_id
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_emprestimos {
        int id PK
        int inscricao_id FK
        int recurso_id FK
        datetime data_emprestimo
        datetime data_devolucao
        datetime data_previsao_devolucao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_atividades_complementares {
        int id PK
        text str_nome
        text str_descricao
        int int_carga_horaria
        int turma_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_atividades_concluidas {
        int id PK
        int inscricao_id FK
        int atividade_id FK
        boolean bool_entregue
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_recursos ||--o{ pc_emprestimos : "recurso tem emprestimos"
    pc_inscricoes ||--o{ pc_emprestimos : "inscricao tem emprestimos"
    pc_turmas ||--o{ pc_atividades_complementares : "turma tem atividades"
    pc_atividades_complementares ||--o{ pc_atividades_concluidas : "atividade tem conclusoes"
    pc_inscricoes ||--o{ pc_atividades_concluidas : "inscricao tem atividades concluidas"
```

## 8. Administracao, Mensagens e Respostas Questionario

```mermaid
erDiagram
    pc_administradores {
        int id PK
        int configuracao_id FK
        int usuario_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_mensagens_sms {
        int id PK
        text str_telefone
        text str_remetente
        text str_mensagem
        int usuario_id FK
        int modulo_id FK
        int curso_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_respostas {
        int inscricao_id PK "PK composta"
        int pergunta_id PK "PK composta"
        int int_resposta
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_administradores }o--|| sg_configuracoes : "configuracao_id"
    pc_administradores }o--|| sg_usuarios : "usuario_id"
    pc_mensagens_sms }o--|| sg_usuarios : "usuario_id"
    pc_mensagens_sms }o--|| sg_modulos : "modulo_id"
    pc_mensagens_sms }o--|| pc_cursos : "curso_id"
    pc_inscricoes ||--o{ pc_respostas : "inscricao tem respostas"
    pc_perguntas ||--o{ pc_respostas : "pergunta tem respostas"
```

---

## 9. Trilhas de Aprendizagem (NOVO)

```mermaid
erDiagram
    pc_trilhas {
        int id PK
        text str_nome
        text str_descricao
        text str_path_imagem
        text str_url_imagem
        int int_ordem
        boolean bool_ativo "default true"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_trilhas_cursos {
        int trilha_id PK_FK "PK composta"
        int curso_id PK_FK "PK composta - apenas int_modalidade=2"
        int int_ordem
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_inscricoes_trilhas {
        int id PK
        int usuario_id FK
        int trilha_id FK
        datetime dt_inscricao
        boolean bool_concluida "default false"
        datetime dt_conclusao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_trilhas ||--o{ pc_trilhas_cursos : "trilha contem cursos"
    pc_cursos ||--o{ pc_trilhas_cursos : "curso pertence a trilhas"
    pc_trilhas ||--o{ pc_inscricoes_trilhas : "trilha tem inscricoes"
    sg_usuarios ||--o{ pc_inscricoes_trilhas : "usuario_id"
    pc_inscricoes_trilhas ||--o{ pc_inscricoes : "inscricao_trilha_id"
```

**Restricao (Service):** `pc_trilhas_cursos.curso_id` so aceita cursos com `int_modalidade = 2`.

## 10. Conteudo Programatico (NOVO)

```mermaid
erDiagram
    pc_conteudos_programaticos {
        int id PK
        text str_titulo
        text str_conteudo
        int int_ordem
        int curso_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_cursos ||--o{ pc_conteudos_programaticos : "curso tem conteudos programaticos"
```

Serve para **ambas** modalidades (online e presencial).

## 11. Modulos e Aulas Online (NOVO)

```mermaid
erDiagram
    pc_modulos_online {
        int id PK
        text str_nome
        text str_descricao
        int int_ordem
        int curso_id FK "apenas int_modalidade=2"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_aulas_online {
        int id PK
        text str_titulo
        text str_descricao
        text str_link_video "URL YouTube etc"
        int int_duracao_video "em segundos"
        int int_ordem
        int modulo_online_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_documentos_aula_online {
        int id PK
        text str_nome_original        
        text str_path
        text str_url
        text str_extensao
        text str_tamanho
        text str_icone
        text str_protocolo
        int int_ordem
        int aula_online_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_cursos ||--o{ pc_modulos_online : "curso tem modulos online"
    pc_modulos_online ||--o{ pc_aulas_online : "modulo tem aulas"
    pc_aulas_online ||--o{ pc_documentos_aula_online : "aula tem documentos PDF"
```

## 12. Questionarios Online (NOVO)

```mermaid
erDiagram
    pc_questionarios_online {
        int id PK
        text str_nome
        int aula_online_id FK "relacao 1-1 com aula"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_perguntas_online {
        int id PK
        text str_enunciado
        int int_ordem
        int questionario_online_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_opcoes_pergunta_online {
        int id PK
        text str_texto
        boolean bool_correta "exatamente 1 por pergunta"
        int int_ordem
        int pergunta_online_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_respostas_online {
        int id PK
        int usuario_id FK
        int pergunta_online_id FK
        int opcao_selecionada_id FK
        boolean bool_acertou "denormalizado"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_questionarios_respondidos_online {
        int id PK
        int usuario_id FK
        int questionario_online_id FK
        int int_acertos
        int int_total_perguntas
        datetime dt_respondido
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_aulas_online ||--o| pc_questionarios_online : "aula tem questionario 1-1"
    pc_questionarios_online ||--o{ pc_perguntas_online : "questionario tem perguntas"
    pc_perguntas_online ||--o{ pc_opcoes_pergunta_online : "pergunta tem opcoes"
    pc_perguntas_online ||--o{ pc_respostas_online : "pergunta recebe respostas"
    pc_opcoes_pergunta_online ||--o{ pc_respostas_online : "opcao selecionada"
    sg_usuarios ||--o{ pc_respostas_online : "usuario_id"
    pc_questionarios_online ||--o{ pc_questionarios_respondidos_online : "tentativas"
    sg_usuarios ||--o{ pc_questionarios_respondidos_online : "usuario_id"
```

**Regras (Service):**
- Cada pergunta: minimo 2 opcoes, exatamente 1 `bool_correta = true`
- Multiplas tentativas: soft-delete respostas anteriores, insere novas

## 13. Progresso e Favoritos (NOVO)

```mermaid
erDiagram
    pc_progressos_aulas_online {
        int id PK
        int usuario_id FK
        int aula_online_id FK
        boolean bool_concluida "default false"
        int int_percentual_video "0-100 default 0"
        datetime dt_conclusao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_favoritos_aulas_online {
        int id PK
        int usuario_id FK
        int aula_online_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_aulas_online ||--o{ pc_progressos_aulas_online : "aula tem progresso"
    sg_usuarios ||--o{ pc_progressos_aulas_online : "usuario_id"
    pc_aulas_online ||--o{ pc_favoritos_aulas_online : "aula favoritada"
    sg_usuarios ||--o{ pc_favoritos_aulas_online : "usuario_id"
```

**Chave logica:** `usuario_id + aula_online_id` (progresso compartilhado entre trilhas).

**Regras de conclusao automatica (Service):**

| Tipo de aula | Condicao de conclusao |
|---|---|
| So texto/PDF (sem video, sem questionario) | Concluida automaticamente ao visualizar |
| Com video (sem questionario) | `int_percentual_video >= 80` |
| Com questionario (sem video) | Questionario respondido |
| Com video + questionario | Video >= 80% **E** questionario respondido |

**Progresso de video:** Frontend envia a cada 10-15s. Service nunca retrocede o percentual.

## 14. Credenciamento: Controle de Acesso e Cracha (NOVO)

```mermaid
erDiagram
    pc_registros_de_acesso {
        int id PK
        int turma_id FK
        int inscricao_id FK
        int int_tipo "1=Entrada 2=Saida"
        datetime dt_registro

        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_layouts_de_cracha {
        int id PK
        text str_nome
        int turma_id FK
        boolean bool_exibir_funcao
        boolean bool_exibir_municipio
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_campos_do_cracha {
        int id PK
        int layout_de_cracha_id FK
        int campo_personalizado_id FK
        int int_ordem
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    pc_turmas ||--o{ pc_registros_de_acesso : "turma tem registros de acesso"
    pc_inscricoes ||--o{ pc_registros_de_acesso : "inscricao tem registros de acesso"
    pc_turmas ||--o| pc_layouts_de_cracha : "turma tem layout de cracha 1-1"
    pc_layouts_de_cracha ||--o{ pc_campos_do_cracha : "layout tem campos variaveis"
    pc_campos_personalizados ||--o{ pc_campos_do_cracha : "campo_personalizado_id"
```

**Notas de Implementacao:**
- **`pc_registros_de_acesso`**: sem UNIQUE — registros duplicados (leituras erroneas) sao permitidos e removidos manualmente pelo atendente. Soft-delete via `removido_em`.
- **`pc_layouts_de_cracha`**: relacao 1-1 com `pc_turmas`. Campos fixos (QR Code, nome do participante, nome do evento) nao sao armazenados — sao sempre impressos.
- **`pc_campos_do_cracha`**: vincula `pc_campos_personalizados` ao layout, com ordenacao por `int_ordem`. A impressao do cracha nao cria registros adicionais — e operacao em tempo de execucao.
- **Enum `TipoDeRegistroDeAcessoEnum`**: `ENTRADA = 1`, `SAIDA = 2` (no Service, nao no banco).

---

## Dependencias Externas (Cross-Module)

| FK no Portal de Cursos | Tabela Externa | Modulo |
|---|---|---|
| `pc_cursos.responsavel_id` | `sg_usuarios` | Gerenciador |
| `pc_cursos.tipo_de_formacao_id` | `sg_tipos_de_formacoes` | Gerenciador |
| `pc_cursos.modelo_de_certificado_participante_id` | `pc_modelos_de_certificados` | Portal de Cursos |
| `pc_cursos.modelo_de_certificado_palestrante_id` | `pc_modelos_de_certificados` | Portal de Cursos |
| `pc_inscricoes.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_palestrantes.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_certificados.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_administradores.configuracao_id` | `sg_configuracoes` | Gerenciador |
| `pc_administradores.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_configuracoes.perfil_usuario_id` | `sg_perfis` | Gerenciador |
| `pc_mensagens_sms.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_mensagens_sms.modulo_id` | `sg_modulos` | Gerenciador |
| `pc_filtros_periodos_letivos.periodo_letivo_id` | `sa_periodos_letivos` | Academico |
| `pc_filtros_unidades.unidade_id` | `sa_unidades` | Academico |
| `pc_filtros_disciplinas.disciplina_id` | `sa_disciplinas` | Academico |
| `pc_filtros_anos_de_escolaridade.ano_de_escolaridade_id` | `sa_anos_de_escolaridade` | Academico |
| `pc_inscricoes_trilhas.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_respostas_online.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_progressos_aulas_online.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_questionarios_respondidos_online.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_favoritos_aulas_online.usuario_id` | `sg_usuarios` | Gerenciador |
| `pc_registros_de_acesso.turma_id` | `pc_turmas` | Portal de Cursos (interno) |
| `pc_registros_de_acesso.inscricao_id` | `pc_inscricoes` | Portal de Cursos (interno) |
| `pc_layouts_de_cracha.turma_id` | `pc_turmas` | Portal de Cursos (interno) |
| `pc_campos_do_cracha.layout_de_cracha_id` | `pc_layouts_de_cracha` | Portal de Cursos (interno) |
| `pc_campos_do_cracha.campo_personalizado_id` | `pc_campos_personalizados` | Portal de Cursos (interno) |
