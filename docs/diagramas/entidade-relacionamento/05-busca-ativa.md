# ER - Modulo Busca Ativa (Prefixo: ba_*)

25 tabelas (22 principais + 3 pivos). Modulo de acompanhamento de alunos em risco de evasao escolar: alertas, casos, visitas tecnicas e formularios.

## 1. Cadastros Base (Niveis de Risco, Situacoes e Categorias)

```mermaid
erDiagram
    ba_niveis_de_risco {
        int id PK
        text str_nome
        varchar str_cor
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_situacoes_de_alerta {
        int id PK
        text str_nome
        varchar str_cor
        int int_tipo_de_alerta "1=Alerta 2=Caso"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_categorias_de_alerta {
        int id PK
        text str_nome
        text str_descricao
        boolean bool_situacao "default true"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_regras_de_categoria {
        int id PK
        text str_nome
        int int_tipo "1=PercFrequencias 2=FreqDias 3=PercNotas 4=Notas 5=NotaGeral"
        int int_operador "1=Maior 2=Menor 3=MaiorIgual 4=MenorIgual 5=Igual"
        decimal dec_valor_comparacao "10,2"
        boolean bool_resposta_esperada
        int nivel_de_risco_id FK
        int categoria_de_alerta_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_motivos_de_encerramento {
        int id PK
        text str_nome
        boolean bool_ativo
        boolean bool_estudante_retornou_aos_estudos
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_conselhos_intersetoriais {
        int id PK
        text str_nome
        boolean bool_ativo
        datetime dt_de_inativacao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_categorias_de_alerta ||--o{ ba_regras_de_categoria : "categoria tem regras"
    ba_niveis_de_risco ||--o{ ba_regras_de_categoria : "nivel de risco tem regras"
```

## 2. Conselhos e Grupos

```mermaid
erDiagram
    ba_grupos_de_conselhos {
        int id PK
        int conselho_intersetorial_id FK
        text str_nome
        boolean bool_ativo
        datetime dt_de_inativacao
        boolean bool_padrao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_membros_do_grupo_de_conselho {
        int id PK
        int grupo_de_conselho_id FK
        int usuario_id FK
        boolean bool_ativo
        datetime dt_de_inativacao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_anexos_do_conselho_intersetorial {
        int id PK
        int conselho_intersetorial_id FK
        text str_nome
        text str_descricao
        text str_path
        text str_url
        varchar str_tipo_mime
        bigint int_tamanho
        int usuario_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_casos_grupo_de_conselhos {
        int id PK
        int caso_id FK
        int grupo_de_conselho_id FK
        datetime criado_em
        datetime atualizado_em
    }

    ba_conselhos_intersetoriais ||--o{ ba_grupos_de_conselhos : "conselho tem grupos"
    ba_conselhos_intersetoriais ||--o{ ba_anexos_do_conselho_intersetorial : "conselho tem anexos"
    ba_grupos_de_conselhos ||--o{ ba_membros_do_grupo_de_conselho : "grupo tem membros"
    ba_grupos_de_conselhos ||--o{ ba_casos_grupo_de_conselhos : "grupo tem casos"
    ba_casos_grupo_de_conselhos }o--|| ba_casos : "caso_id"
    ba_membros_do_grupo_de_conselho }o--|| sg_usuarios : "usuario_id"
    ba_anexos_do_conselho_intersetorial }o--o| sg_usuarios : "usuario_id"
```

## 3. Regioes e Associacoes

```mermaid
erDiagram
    ba_regioes {
        int id PK
        text str_nome
        text str_descricao
        boolean bool_ativo "default true"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_regioes_bairros {
        int regiao_id FK
        int bairro_id FK
        datetime criado_em
        datetime atualizado_em
    }

    ba_regioes_unidades {
        int regiao_id FK
        int unidade_id FK
        datetime criado_em
        datetime atualizado_em
    }

    ba_regioes ||--o{ ba_regioes_bairros : "regiao tem bairros"
    ba_regioes ||--o{ ba_regioes_unidades : "regiao tem unidades"
    ba_regioes_bairros }o--|| sg_bairros : "bairro_id"
    ba_regioes_unidades }o--|| sa_unidades : "unidade_id"
```

## 4. Casos e Alertas

```mermaid
erDiagram
    ba_casos {
        int id PK
        int aluno_id FK
        int periodo_letivo_id FK
        int unidade_id FK
        int situacao_de_alerta_id FK
        int motivo_de_encerramento_id FK
        text str_justificativa
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_alertas {
        int id PK
        int caso_id FK
        int categoria_de_alerta_id FK
        int regra_de_categoria_id FK
        int nivel_de_risco_id FK
        int situacao_de_alerta_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_anexos_do_caso {
        int id PK
        int caso_id FK
        text str_nome
        text str_descricao
        text str_path
        text str_url
        varchar str_tipo_mime
        bigint int_tamanho
        int usuario_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_casos ||--o{ ba_alertas : "caso tem alertas"
    ba_casos ||--o{ ba_anexos_do_caso : "caso tem anexos"
    ba_casos }o--|| sa_alunos : "aluno_id"
    ba_casos }o--|| sa_periodos_letivos : "periodo_letivo_id"
    ba_casos }o--|| sa_unidades : "unidade_id"
    ba_casos }o--o| ba_situacoes_de_alerta : "situacao_de_alerta_id"
    ba_casos }o--o| ba_motivos_de_encerramento : "motivo_de_encerramento_id"
    ba_alertas }o--|| ba_categorias_de_alerta : "categoria_de_alerta_id"
    ba_alertas }o--|| ba_regras_de_categoria : "regra_de_categoria_id"
    ba_alertas }o--|| ba_niveis_de_risco : "nivel_de_risco_id"
    ba_alertas }o--|| ba_situacoes_de_alerta : "situacao_de_alerta_id"
    ba_anexos_do_caso }o--o| sg_usuarios : "usuario_id"
```

## 5. Formularios de Visita Tecnica

```mermaid
erDiagram
    ba_tipos_de_formulario_de_visita_tecnica {
        int id PK
        text str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_formularios_de_visita_tecnica {
        int id PK
        text str_nome
        text str_descricao
        int tipo_de_formulario_de_visita_tecnica_id FK
        boolean bool_ativo "default true"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_secoes_de_formulario_de_visita_tecnica {
        int id PK
        text str_nome
        int formulario_de_visita_tecnica_id FK
        int int_ordem "default 0"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_campos_de_formulario_de_visita_tecnica {
        int id PK
        text str_nome
        int secao_de_formulario_de_visita_tecnica_id FK
        int int_tipo_de_campo "1=TextoCurto 2=TextoLongo 3=OpcaoUnica 4=MultiplaEscolha 5=Data 6=Hora 7=Numero"
        boolean bool_obrigatorio "default false"
        int int_ordem "default 0"
        json json_opcoes
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_tipos_de_formulario_de_visita_tecnica ||--o{ ba_formularios_de_visita_tecnica : "tipo tem formularios"
    ba_formularios_de_visita_tecnica ||--o{ ba_secoes_de_formulario_de_visita_tecnica : "formulario tem secoes"
    ba_secoes_de_formulario_de_visita_tecnica ||--o{ ba_campos_de_formulario_de_visita_tecnica : "secao tem campos"
```

## 6. Visitas Tecnicas e Respostas

```mermaid
erDiagram
    ba_visitas_tecnicas {
        int id PK
        int caso_id FK
        int formulario_de_visita_tecnica_id FK
        date dt_visita
        time hr_visita
        text str_observacoes
        int usuario_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_respostas_de_visita_tecnica {
        int id PK
        int visita_tecnica_id FK
        int campo_de_formulario_de_visita_tecnica_id FK
        text str_resposta
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_visitas_tecnicas ||--o{ ba_respostas_de_visita_tecnica : "visita tem respostas"
    ba_visitas_tecnicas }o--|| ba_casos : "caso_id"
    ba_visitas_tecnicas }o--|| ba_formularios_de_visita_tecnica : "formulario_id"
    ba_visitas_tecnicas }o--o| sg_usuarios : "usuario_id"
    ba_respostas_de_visita_tecnica }o--|| ba_campos_de_formulario_de_visita_tecnica : "campo_id"
```

## 7. Fluxo do Caso (Timeline)

```mermaid
erDiagram
    ba_fluxos_do_caso {
        int id PK
        int caso_id FK
        int int_tipo "1=Alerta 2=MudancaStatus 3=VisitaTecnica 4=ContatoResponsavel 5=Documento"
        text str_descricao
        int situacao_anterior_id FK
        int situacao_nova_id FK
        int alerta_id FK
        int visita_tecnica_id FK
        int anexo_id FK
        int usuario_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_fluxos_do_caso }o--|| ba_casos : "caso_id"
    ba_fluxos_do_caso }o--o| ba_situacoes_de_alerta : "situacao_anterior_id"
    ba_fluxos_do_caso }o--o| ba_situacoes_de_alerta : "situacao_nova_id"
    ba_fluxos_do_caso }o--o| ba_alertas : "alerta_id"
    ba_fluxos_do_caso }o--o| ba_visitas_tecnicas : "visita_tecnica_id"
    ba_fluxos_do_caso }o--o| ba_anexos_do_caso : "anexo_id"
    ba_fluxos_do_caso }o--o| sg_usuarios : "usuario_id"
```

## 8. Frequencias da Coordenacao

```mermaid
erDiagram
    ba_frequencias_coordenacao {
        int id PK
        int turma_id FK
        int int_qtd_de_faltas
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_frequencias_coordenacao }o--|| sa_turmas : "turma_id"

    ba_alertas_coordenacao {
        int id PK
        int turma_id FK
        text str_mensagem
        text str_justificativa
        boolean bool_resolvido
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ba_alertas_coordenacao }o--|| sa_turmas : "turma_id"
```

## 9. Visao Geral - Relacionamentos entre Entidades

```mermaid
erDiagram
    ba_niveis_de_risco ||--o{ ba_regras_de_categoria : ""
    ba_niveis_de_risco ||--o{ ba_alertas : ""
    ba_categorias_de_alerta ||--o{ ba_regras_de_categoria : ""
    ba_categorias_de_alerta ||--o{ ba_alertas : ""
    ba_situacoes_de_alerta ||--o{ ba_casos : ""
    ba_situacoes_de_alerta ||--o{ ba_alertas : ""
    ba_casos ||--o{ ba_alertas : ""
    ba_casos ||--o{ ba_anexos_do_caso : ""
    ba_casos ||--o{ ba_visitas_tecnicas : ""
    ba_casos ||--o{ ba_fluxos_do_caso : ""
    ba_tipos_de_formulario_de_visita_tecnica ||--o{ ba_formularios_de_visita_tecnica : ""
    ba_formularios_de_visita_tecnica ||--o{ ba_secoes_de_formulario_de_visita_tecnica : ""
    ba_formularios_de_visita_tecnica ||--o{ ba_visitas_tecnicas : ""
    ba_secoes_de_formulario_de_visita_tecnica ||--o{ ba_campos_de_formulario_de_visita_tecnica : ""
    ba_campos_de_formulario_de_visita_tecnica ||--o{ ba_respostas_de_visita_tecnica : ""
    ba_visitas_tecnicas ||--o{ ba_respostas_de_visita_tecnica : ""
    ba_regioes ||--o{ ba_regioes_bairros : ""
    ba_regioes ||--o{ ba_regioes_unidades : ""
    ba_conselhos_intersetoriais ||--o{ ba_grupos_de_conselhos : ""
    ba_conselhos_intersetoriais ||--o{ ba_anexos_do_conselho_intersetorial : ""
    ba_grupos_de_conselhos ||--o{ ba_membros_do_grupo_de_conselho : ""
    ba_grupos_de_conselhos ||--o{ ba_casos_grupo_de_conselhos : ""
    ba_casos ||--o{ ba_casos_grupo_de_conselhos : ""
    ba_motivos_de_encerramento ||--o{ ba_casos : ""
    ba_frequencias_coordenacao }o--|| sa_turmas : ""
    ba_alertas_coordenacao }o--|| sa_turmas : ""
```

## Dependencias Externas (Cross-Module)

| FK no Busca Ativa | Tabela Externa | Modulo |
|---|---|---|
| `ba_casos.aluno_id` | `sa_alunos` | Academico |
| `ba_casos.periodo_letivo_id` | `sa_periodos_letivos` | Academico |
| `ba_casos.unidade_id` | `sa_unidades` | Academico |
| `ba_regioes_bairros.bairro_id` | `sg_bairros` | Gerenciador |
| `ba_regioes_unidades.unidade_id` | `sa_unidades` | Academico |
| `ba_anexos_do_caso.usuario_id` | `sg_usuarios` | Gerenciador |
| `ba_visitas_tecnicas.usuario_id` | `sg_usuarios` | Gerenciador |
| `ba_fluxos_do_caso.usuario_id` | `sg_usuarios` | Gerenciador |
| `ba_membros_do_grupo_de_conselho.usuario_id` | `sg_usuarios` | Gerenciador |
| `ba_anexos_do_conselho_intersetorial.usuario_id` | `sg_usuarios` | Gerenciador |
| `ba_frequencias_coordenacao.turma_id` | `sa_turmas` | Academico |
| `ba_alertas_coordenacao.turma_id` | `sa_turmas` | Academico |
