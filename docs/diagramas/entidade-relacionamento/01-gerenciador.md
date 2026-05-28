# ER - Modulo Gerenciador (Prefixo: sg_*)

52 tabelas. Base do sistema: pessoas, usuarios, permissoes, localidades, mensagens e configuracoes.

## 1. Pessoas e Usuarios

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
        string str_habilitacao
        string str_ctps
        string str_titulo_de_eleitor
        string str_pis_pasep
        string str_certificado_de_reservista
        string str_conjuge
        boolean bool_possui_laudo_necessidade_especial
        boolean bool_usuario_emancipado
        int int_pontos_gamificacao
        int necessidade_especial_id FK
        int etnia_id FK
        int nacionalidade_id FK
        int naturalidade_id FK
        int uf_naturalidade_id FK
        int profissao_id FK
    }

    sg_usuarios {
        int id PK
        string str_login
        string str_senha
        string remember_token
        int int_tipo_de_usuario
        boolean bool_bloqueado
        boolean bool_primeiro_acesso
        boolean bool_superusuario
        boolean bool_acesso_api
        boolean bool_usuario_emancipado
        int int_numero_de_erros_de_senha
        timestamp dt_ultimo_acesso
        timestamp dt_ultima_troca_de_senha
        string str_url_assinatura
        string str_url_imagem_usuario
        string str_certificado_nome_responsavel
        string str_certificado_cargo_responsavel
        string str_certificado_decreto
        int pessoa_id FK
        int usuario_cadastrador_id FK
    }

    sg_rgs {
        int id PK
        string str_rg
        string str_orgao_emissor
        timestamp dt_emissao
        int estado_emissao_id FK
        int pessoa_id FK
    }

    sg_historicos_de_senhas {
        int id PK
        int usuario_id FK
        string str_senha
    }

    sg_recuperacao_senhas {
        int id PK
        string str_token
        boolean bool_utilizado
        int int_tentativas
        int usuario_id FK
    }

    sg_historico_de_acessos {
        int id PK
        int usuario_id FK
    }

    sg_certificados_usuarios {
        int id PK
        string str_descricao
        string str_path
        boolean bool_ativo
        int usuario_id FK
    }

    sg_cpfs_autorizados {
        int id PK
        string str_cpf
    }

    sg_pessoas ||--o{ sg_usuarios : "pessoa tem usuarios"
    sg_pessoas ||--o{ sg_rgs : "pessoa tem RGs"
    sg_usuarios ||--o{ sg_historicos_de_senhas : "usuario tem historico senhas"
    sg_usuarios ||--o{ sg_recuperacao_senhas : "usuario tem recuperacoes"
    sg_usuarios ||--o{ sg_historico_de_acessos : "usuario tem historico acessos"
    sg_usuarios ||--o{ sg_certificados_usuarios : "usuario tem certificados"
    sg_pessoas }o--o| sg_necessidades_especiais : "necessidade_especial_id"
    sg_pessoas }o--o| sg_profissoes : "profissao_id"
```

## 2. Permissoes e Modulos

```mermaid
erDiagram
    sg_perfis {
        int id PK
        string str_nome
        boolean bool_habilitar_chat
        int int_qtd_de_dias_sem_acesso_para_remover
        boolean bool_ignorar_troca_de_senha
        boolean bool_tecnico_de_suporte
    }

    sg_usuarios_perfis {
        int id PK
        int usuario_id FK
        int perfil_id FK
    }

    sg_modulos {
        int id PK
        string str_nome UK
        string str_icone
        string str_cor_primaria
        string str_cor_secundaria
        boolean bool_ativado
        int int_ordem
    }

    sg_menus {
        int id PK
        string str_nome
        string str_icone
        int int_ordem
        int modulo_id FK
        int menu_pai_id FK
    }

    sg_funcionalidades {
        int id PK
        string str_rota
        string str_nome
        string str_icone
        boolean bool_menu
        int int_ordem
        int menu_id FK
        int grupo_funcionalidade_id FK
    }

    sg_grupos_funcionalidades {
        int id PK
        string str_nome
        int modulo_id FK
    }

    sg_perfis_funcionalidades {
        int id PK
        boolean bool_acesso
        int perfil_id FK
        int funcionalidade_id FK
    }

    sg_perfis_personalizados {
        int id PK
        boolean bool_acesso
        int usuario_id FK
        int funcionalidade_id FK
    }

    sg_favoritos {
        int id PK
        int usuario_id FK
        int funcionalidade_id FK
        int modulo_id FK
    }

    sg_usuarios ||--o{ sg_usuarios_perfis : "usuario tem perfis"
    sg_perfis ||--o{ sg_usuarios_perfis : "perfil tem usuarios"
    sg_perfis ||--o{ sg_perfis_funcionalidades : "perfil tem funcionalidades"
    sg_funcionalidades ||--o{ sg_perfis_funcionalidades : "funcionalidade em perfis"
    sg_usuarios ||--o{ sg_perfis_personalizados : "usuario tem permissoes custom"
    sg_funcionalidades ||--o{ sg_perfis_personalizados : "funcionalidade personalizada"
    sg_modulos ||--o{ sg_menus : "modulo tem menus"
    sg_modulos ||--o{ sg_grupos_funcionalidades : "modulo tem grupos"
    sg_menus ||--o{ sg_funcionalidades : "menu tem funcionalidades"
    sg_menus ||--o{ sg_menus : "menu pai-filho"
    sg_grupos_funcionalidades ||--o{ sg_funcionalidades : "grupo tem funcionalidades"
    sg_usuarios ||--o{ sg_favoritos : "usuario tem favoritos"
```

## 3. Localidades

```mermaid
erDiagram
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
        int int_codigo_ibge
        int estado_id FK
        int pais_id FK
    }

    sg_bairros {
        int id PK
        string str_nome
        int int_localidade
        int cidade_id FK
        int estado_id FK
        int pais_id FK
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

    sg_enderecos_avulsos {
        int id PK
        string str_logradouro
        string str_numero
        string str_complemento
        string str_bairro
        string str_cidade
        string str_estado
        string str_cep
        int pessoa_id FK
    }

    sg_paises ||--o{ sg_estados : "pais tem estados"
    sg_estados ||--o{ sg_cidades : "estado tem cidades"
    sg_cidades ||--o{ sg_bairros : "cidade tem bairros"
    sg_bairros ||--o{ sg_enderecos : "bairro tem enderecos"
    sg_cidades ||--o{ sg_enderecos : "cidade tem enderecos"
    sg_estados ||--o{ sg_enderecos : "estado tem enderecos"
    sg_pessoas ||--o{ sg_enderecos : "pessoa tem enderecos"
    sg_pessoas ||--o{ sg_enderecos_avulsos : "pessoa tem enderecos avulsos"
```

## 4. Telefones

```mermaid
erDiagram
    sg_tipos_telefones {
        int id PK
        string str_nome
    }

    sg_telefones {
        int id PK
        string str_numero
        string str_observacao
        boolean bool_telefone_para_recuperacao_de_senha
        int tipo_telefone_id FK
        int pessoa_id FK
        int editora_id FK
        int biblioteca_id FK
        int fornecedor_id FK
    }

    sg_telefones_unidades {
        int id PK
        string str_numero
        string str_observacao
        int unidade_id FK
        int tipo_telefone_id FK
    }

    sg_telefones_adicionais_sms {
        int id PK
        string str_telefone
        int modulo_id FK
    }

    sg_tipos_telefones ||--o{ sg_telefones : "tipo tem telefones"
    sg_pessoas ||--o{ sg_telefones : "pessoa tem telefones"
    sg_tipos_telefones ||--o{ sg_telefones_unidades : "tipo tem telefones unidade"
```

## 5. Mensagens e Comunicacao

```mermaid
erDiagram
    sg_mensagens {
        int id PK
        string str_assunto
        string str_conteudo
        int int_tipo
        int remetente_id FK
        int modulo_id FK
    }

    sg_mensagens_usuarios {
        int id PK
        boolean bool_lido
        boolean bool_arquivado
        int mensagem_id FK
        int destinatario_id FK
    }

    sg_mensagens_emails {
        int id PK
        string str_email_remetente
        string str_email_destinatario
        string str_assunto
        string str_mensagem
        int int_tipo_de_envio
        int int_tipo_de_email
        int int_status
        int modulo_id FK
        int remetente_id FK
        int destinatario_id FK
    }

    sg_mensagens_sms {
        int id PK
        string str_telefone
        string str_remetente
        string str_mensagem
        int int_status
        int int_tipo_de_envio
        int int_referencia
        timestamp dt_de_envio
        int usuario_remetente_id FK
        int modulo_id FK
        int destinatario_id FK
        int remetente_id FK
    }

    sg_controles_envios_mensagens {
        int id PK
        string str_ano
        string str_mes
        int int_limite_de_envio_sms_automatico
        int int_qtd_sms_automatico_enviados
        int int_limite_de_envio_sms_manual
        int int_qtd_sms_manual_enviados
        int int_limite_de_envio_email_automatico
        int int_qtd_email_automatico_enviados
        int int_limite_de_envio_email_manual
        int int_qtd_email_manual_enviados
        int modulo_id FK
    }

    sg_configuracoes_envios_mensagens {
        int id PK
        int int_limite_sms_manual
        int int_limite_sms_automatico
        int int_limite_email_manual
        int int_limite_email_automatico
        int modulo_id FK
        int configuracao_id FK
    }

    sg_mensagens ||--o{ sg_mensagens_usuarios : "mensagem tem destinatarios"
    sg_modulos ||--o{ sg_mensagens : "modulo tem mensagens"
    sg_modulos ||--o{ sg_mensagens_emails : "modulo tem emails"
    sg_modulos ||--o{ sg_mensagens_sms : "modulo tem sms"
    sg_modulos ||--o{ sg_controles_envios_mensagens : "modulo tem controles"
    sg_modulos ||--o{ sg_configuracoes_envios_mensagens : "modulo tem config envios"
```

## 6. Configuracoes e Cliente

```mermaid
erDiagram
    sg_cliente {
        int id PK
        string str_nome
        string str_razao_social
        string str_cnpj
        string str_orgao
        string str_superintendencia
        string str_telefone
        string str_email
        string str_nome_estado
        string str_nome_do_prefeito
        string str_url_logo
        string str_url_logo_quadrada
        string str_url_logo_branca
        string str_url_logo_relatorio
        int endereco_id FK
        int secretario_municipal_id FK
    }

    sg_configuracoes {
        int id PK
        string str_mascara_matricula
        int int_quantidade_registros_por_pagina
        boolean bool_em_manutencao
        string str_mensagem_manutencao
        boolean bool_ambiente_de_homologacao
        boolean bool_integrar_academico_pre_matricula
        boolean bool_integrar_portal_de_cursos_e_academico
        boolean bool_buscar_cep_automatico
        boolean bool_cpf_obrigatorio_na_matricula
        boolean bool_informar_data_de_matricula_manual
        boolean bool_cadastro_por_cpf_autorizado
        boolean bool_pagina_home_simplificada
        boolean bool_notificacao_por_email
        boolean bool_notificacao_por_sms
        int int_sequencial_matricula
        int int_maximo_de_tentativas_de_login
        int int_prazo_para_solicitar_troca_de_senha
        int perfil_id_publico_geral
        int perfil_professor_id_academico
        int perfil_responsavel_id_academico
        int perfil_aluno_id_academico
        int perfil_secretario_id_academico
        int perfil_responsavel_id_gestao_de_vagas
        int perfil_servidor_id_gestao_de_pessoas
    }

    sg_configuracoes_home {
        int id PK
        string str_titulo_home
        string str_descricao_home
        string str_texto_informativo_home
        string str_url_banner
        boolean bool_exibir_modulo_academico
        boolean bool_exibir_modulo_pre_matricula
        boolean bool_exibir_modulo_portal_de_cursos
        boolean bool_exibir_modulo_remocao_lotacao
        boolean bool_exibir_modulo_biblioteca
        boolean bool_exibir_modulo_processo_seletivo
        boolean bool_exibir_modulo_diagnostico
        boolean bool_exibir_modulo_progressao_funcional
        boolean bool_exibir_geo_escolar
        int int_persona_padrao_da_home
    }

    sg_imagens_tela_login {
        int id PK
        string str_path_imagem
    }

    sg_secoes_da_home {
        int id PK
        string str_nome
        string str_subtitulo
        string str_descricao
        string foto_path
        int int_ordem_visao_do_responsavel
        int int_ordem_visao_do_servidor
        boolean bool_ativo
        int modulo_id FK
    }

    sg_servicos_rapidos {
        int id PK
        string str_nome
        string str_icone
        string str_url
        string str_cor
        int int_ordem
        boolean bool_ativo
        int int_visao_da_home
    }

    sg_equipe_de_gestao {
        int id PK
        string str_nome
        string str_funcao
        string foto_path
    }
```

## 7. Sistema e Operacional

```mermaid
erDiagram
    sg_logs {
        int id PK
        string str_nome_rota
        string str_path_rota
        string str_ip
        string str_metodo
        string str_atributos
        int usuario_id FK
    }

    sg_importacoes {
        int id PK
        string str_nome_original_arquivo
        string str_path_servidor
        string str_resultado_processamento
        int int_status
        int int_modelo_referencia
        timestamp dt_inicio_processamento
        timestamp dt_termino_processamento
    }

    sg_versoes {
        int id PK
        string str_classe_seed_executada
        string str_versao_atual
        string str_notas
    }

    sg_indicadores {
        int id PK
        string str_nome
        string str_view
        string str_conteudo
        int int_guia
        int int_ordem_na_guia
        int modulo_id FK
    }

    sg_layout_paineis {
        int id PK
        int int_segundos_para_troca
        int int_unidade_de_altura
        int usuario_id FK
        int modulo_id FK
    }

    sg_elementos_do_painel {
        int id PK
        string str_texto
        string str_cor_texto
        int int_tipo_de_elemento
        int int_pagina
        int int_ordem
        float float_topo
        float float_esquerda
        float float_largura
        float float_altura
        int indicador_id FK
        int layout_painel_id FK
    }

    sg_pontos_gamificacao {
        int id PK
        int int_pontos
        int pessoa_id FK
        int modulo_id FK
    }

    sg_usuarios ||--o{ sg_logs : "usuario tem logs"
    sg_modulos ||--o{ sg_indicadores : "modulo tem indicadores"
    sg_usuarios ||--o{ sg_layout_paineis : "usuario tem paineis"
    sg_indicadores ||--o{ sg_elementos_do_painel : "indicador tem elementos"
    sg_layout_paineis ||--o{ sg_elementos_do_painel : "painel tem elementos"
    sg_pessoas ||--o{ sg_pontos_gamificacao : "pessoa tem pontos"
```

## 8. Tabelas Auxiliares

```mermaid
erDiagram
    sg_necessidades_especiais {
        int id PK
        string str_nome
        string str_sigla
        boolean bool_frequentar_sala_aee_sem_laudo
    }

    sg_profissoes {
        int id PK
        string str_codigo
        string str_ocupacao
    }

    sg_cids {
        int id PK
        string str_codigo
        string str_descricao
        string str_codigo_cid_11
    }

    sg_tipos_de_formacoes {
        int id PK
        string str_nome
        int int_pontos
        int int_validade
        boolean bool_nao_expira
    }

    sg_itens_atualizacao_ava {
        int id PK
        int int_tipo_modelo
        int int_id_modelo
        int int_numero_tentativas
        boolean bool_processado
    }
```

## Resumo das 52 tabelas sg_*

| Grupo | Tabelas | Quantidade |
|-------|---------|------------|
| Pessoas e Usuarios | sg_pessoas, sg_usuarios, sg_rgs, sg_historicos_de_senhas, sg_recuperacao_senhas, sg_historico_de_acessos, sg_certificados_usuarios, sg_cpfs_autorizados | 8 |
| Permissoes e Modulos | sg_perfis, sg_usuarios_perfis, sg_modulos, sg_menus, sg_funcionalidades, sg_grupos_funcionalidades, sg_perfis_funcionalidades, sg_perfis_personalizados, sg_favoritos | 9 |
| Localidades | sg_paises, sg_estados, sg_cidades, sg_bairros, sg_enderecos, sg_enderecos_avulsos | 6 |
| Telefones | sg_tipos_telefones, sg_telefones, sg_telefones_unidades, sg_telefones_adicionais_sms | 4 |
| Mensagens | sg_mensagens, sg_mensagens_usuarios, sg_mensagens_emails, sg_mensagens_sms, sg_controles_envios_mensagens, sg_configuracoes_envios_mensagens | 6 |
| Configuracoes | sg_cliente, sg_configuracoes, sg_configuracoes_home, sg_imagens_tela_login, sg_secoes_da_home, sg_servicos_rapidos, sg_equipe_de_gestao | 7 |
| Sistema | sg_logs, sg_importacoes, sg_versoes, sg_indicadores, sg_layout_paineis, sg_elementos_do_painel, sg_pontos_gamificacao | 7 |
| Auxiliares | sg_necessidades_especiais, sg_profissoes, sg_cids, sg_tipos_de_formacoes, sg_itens_atualizacao_ava | 5 |
| **Total** | | **52** |
