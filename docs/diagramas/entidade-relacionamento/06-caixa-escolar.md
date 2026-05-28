# ER - Modulo Caixa Escolar (Prefixo: ce_*)

56 tabelas. Modulo de gestao financeira escolar: contas bancarias, receitas, despesas, cotacoes de preco, conselhos, programas governamentais, conciliacao bancaria, doacoes e categorias de fornecedores.

> **Fluxo de vinculacao de fornecedores:** Fornecedores sao classificados por categorias (`ce_categorias_do_fornecedor`). Na tela de Cotacao de Preco, itens podem ser vinculados a fornecedores (`ce_fornecedores_itens_da_cotacao_de_preco`). Na guia Lancamento, ao adicionar cotacao recebida, o sistema filtra apenas itens vinculados ao fornecedor selecionado (ou todos se nenhum vinculo existir).

## 1. Infraestrutura Bancaria

```mermaid
erDiagram
    ce_bancos {
        int id PK
        citext str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_contas_bancarias {
        int id PK
        int unidade_id FK
        int banco_id FK
        int int_tipo_de_conta
        text str_agencia
        text str_conta
        boolean bool_caixa_interno
        decimal dec_saldo "15,2"
        decimal dec_saldo_custeio "15,2"
        decimal dec_saldo_capital "15,2"
        decimal dec_saldo_servico "15,2"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_saldos_de_contas_bancarias {
        int id PK
        int conta_bancaria_id FK
        int ano_de_exercicio_financeiro_id FK
        decimal dec_saldo_inicial "15,2"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_movimentacoes_da_conta_bancaria {
        int id PK
        int int_tipo_de_movimentacao
        decimal dec_valor "15,2"
        int despesa_id FK
        int receita_id FK
        int conta_bancaria_id FK
        int usuario_id FK
        date dt_de_compentencia
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_transferencias_bancarias {
        int id PK
        int conta_bancaria_de_origem_id FK
        int conta_bancaria_de_destino_id FK
        decimal dec_valor_custeio "15,2"
        decimal dec_valor_capital "15,2"
        decimal dec_valor_servico "15,2"
        date dt_de_transferencia
        int categoria_de_origem_id FK
        int categoria_de_destino_id FK
        int programa_governamental_da_unidade_de_origem_id FK
        int programa_governamental_da_unidade_de_destino_id FK
        int despesa_origem_id FK
        int receita_destino_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_bancos ||--o{ ce_contas_bancarias : "banco tem contas"
    ce_contas_bancarias ||--o{ ce_saldos_de_contas_bancarias : "conta tem saldos"
    ce_contas_bancarias ||--o{ ce_movimentacoes_da_conta_bancaria : "conta tem movimentacoes"
    ce_contas_bancarias ||--o{ ce_transferencias_bancarias : "conta origem"
    ce_contas_bancarias ||--o{ ce_transferencias_bancarias : "conta destino"
    ce_contas_bancarias }o--|| sa_unidades : "unidade_id"
```

## 2. Programas Governamentais e Plano de Contas

```mermaid
erDiagram
    ce_programas_governamentais {
        int id PK
        citext str_nome
        citext str_sigla
        boolean bool_ativo "default false"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_programas_governamentais_da_unidade {
        int id PK
        int unidade_id FK
        int conta_bancaria_id FK
        int programa_governamental_id FK
        boolean bool_ativo "default false"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_planos_de_contas {
        int id PK
        citext str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_categorias {
        int id PK
        citext str_nome
        citext str_codigo
        int int_tipo_de_categoria
        int plano_de_contas_id FK
        int int_nivel
        int int_ordem
        int categoria_id FK "auto-referencia pai"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_unidades_do_plano_de_contas {
        int id PK
        int unidade_id FK
        int plano_de_contas_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_categorias_da_unidade {
        int id PK
        int unidade_do_plano_de_contas_id FK
        int categoria_id FK
        int programa_governamental_da_unidade_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_programas_governamentais ||--o{ ce_programas_governamentais_da_unidade : "programa tem unidades"
    ce_programas_governamentais_da_unidade }o--|| sa_unidades : "unidade_id"
    ce_programas_governamentais_da_unidade }o--|| ce_contas_bancarias : "conta_bancaria_id"
    ce_planos_de_contas ||--o{ ce_categorias : "plano tem categorias"
    ce_planos_de_contas ||--o{ ce_unidades_do_plano_de_contas : "plano tem unidades"
    ce_categorias ||--o{ ce_categorias : "categoria pai-filho"
    ce_unidades_do_plano_de_contas ||--o{ ce_categorias_da_unidade : "unidade do plano tem categorias"
    ce_categorias ||--o{ ce_categorias_da_unidade : "categoria_id"
    ce_programas_governamentais_da_unidade ||--o{ ce_categorias_da_unidade : "programa_gov_unidade_id"
```

## 3. Ano de Exercicio Financeiro e Gestao de Recursos

```mermaid
erDiagram
    ce_anos_de_exercicio_financeiro {
        int id PK
        citext str_nome
        citext str_sigla
        citext str_ano
        date dt_de_inicio
        date dt_de_termino
        boolean bool_padrao "default false"
        int ano_de_exercicio_financeiro_anterior_id FK "auto-referencia"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_reprogramacoes_de_recursos {
        int id PK
        int ano_de_exercicio_financeiro_de_origem_id FK
        int ano_de_exercicio_financeiro_de_destino_id FK
        int programa_governamental_da_unidade_id FK
        date dt_de_competencia
        decimal dec_valor_devolvido_de_custeio "15,2"
        decimal dec_valor_devolvido_de_capital "15,2"
        decimal dec_valor_total "15,2"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_devolucoes_de_recursos {
        int id PK
        int ano_de_exercicio_financeiro_id FK
        int programa_governamental_da_unidade_id FK
        string str_numero_gru
        date dt_de_devolucao
        decimal dec_valor_devolvido_de_custeio "15,2"
        decimal dec_valor_devolvido_de_capital "15,2"
        decimal dec_valor_devolvido_de_servico "15,2"
        decimal dec_valor_total "15,2"
        boolean bool_recurso_nao_utilizado
        string str_justificativa
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_devolucao_de_recursos {
        int id PK
        citext str_nome
        string str_path
        string str_url
        int devolucao_de_recurso_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anos_de_exercicio_financeiro ||--o{ ce_anos_de_exercicio_financeiro : "ano anterior"
    ce_anos_de_exercicio_financeiro ||--o{ ce_reprogramacoes_de_recursos : "ano origem"
    ce_anos_de_exercicio_financeiro ||--o{ ce_reprogramacoes_de_recursos : "ano destino"
    ce_anos_de_exercicio_financeiro ||--o{ ce_devolucoes_de_recursos : "ano tem devolucoes"
    ce_programas_governamentais_da_unidade ||--o{ ce_reprogramacoes_de_recursos : "programa tem reprogramacoes"
    ce_programas_governamentais_da_unidade ||--o{ ce_devolucoes_de_recursos : "programa tem devolucoes"
    ce_devolucoes_de_recursos ||--o{ ce_anexos_devolucao_de_recursos : "devolucao tem anexos"
```

## 4. Conselhos e Governanca

```mermaid
erDiagram
    ce_conselhos {
        int id PK
        citext str_nome
        int unidade_id FK
        date dt_inicial
        date dt_final
        text str_descricao_da_averbacao
        text str_destinatario_da_averbacao
        int representante_da_averbacao_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_membros_do_conselho {
        int id PK
        int pessoa_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_funcoes_do_membro_do_conselho {
        int id PK
        int conselho_id FK
        int membro_do_conselho_id FK
        int int_funcao
        date dt_de_entrada
        date dt_de_saida
        text str_identificacao
        int int_situacao
        int int_conselho
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_de_conselho {
        int id PK
        citext str_nome
        text str_path
        text str_url
        int conselho_id FK
        text str_tamanho
        text str_icone
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_atas_de_conselho {
        int id PK
        citext str_nome
        citext str_descricao
        text str_path
        text str_url
        int conselho_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_editais_de_convocacao {
        int id PK
        text str_descricao
        text str_numero
        text str_ano
        date dt_de_publicacao
        int conselho_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_conselhos ||--o{ ce_funcoes_do_membro_do_conselho : "conselho tem funcoes"
    ce_conselhos ||--o{ ce_anexos_de_conselho : "conselho tem anexos"
    ce_conselhos ||--o{ ce_atas_de_conselho : "conselho tem atas"
    ce_conselhos ||--o{ ce_editais_de_convocacao : "conselho tem editais"
    ce_conselhos }o--|| sa_unidades : "unidade_id"
    ce_membros_do_conselho ||--o{ ce_funcoes_do_membro_do_conselho : "membro tem funcoes"
    ce_membros_do_conselho }o--|| sg_pessoas : "pessoa_id"
```

## 5. Pareceres e Termos de Compromisso

```mermaid
erDiagram
    ce_modelos_de_parecer {
        int id PK
        citext str_nome
        text str_descricao
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_pareceres_do_conselho {
        int id PK
        citext str_nome
        text str_descricao
        int conselho_id FK
        int programa_governamental_id FK
        int modelo_de_parecer_id FK
        datetime dt_competencia
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_do_parecer {
        int id PK
        int parecer_do_conselho_id FK
        string str_path
        string str_url
        string str_nome_original
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_termos_de_compromisso {
        int id PK
        citext str_nome
        int conselho_id FK
        int programa_governamental_da_unidade_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_do_termo_de_compromisso {
        int id PK
        citext str_nome
        string str_path
        string str_url
        int termo_de_compromisso_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_modelos_de_parecer ||--o{ ce_pareceres_do_conselho : "modelo tem pareceres"
    ce_pareceres_do_conselho ||--o{ ce_anexos_do_parecer : "parecer tem anexos"
    ce_pareceres_do_conselho }o--|| ce_conselhos : "conselho_id"
    ce_pareceres_do_conselho }o--|| ce_programas_governamentais : "programa_gov_id"
    ce_termos_de_compromisso ||--o{ ce_anexos_do_termo_de_compromisso : "termo tem anexos"
    ce_termos_de_compromisso }o--|| ce_conselhos : "conselho_id"
    ce_termos_de_compromisso }o--|| ce_programas_governamentais_da_unidade : "programa_gov_unidade_id"
```

## 6. Fornecedores e Parcerias

```mermaid
erDiagram
    ce_fornecedores {
        int id PK
        citext str_nome
        citext str_razao_social
        int int_tipo_de_pessoa
        text str_cpf_cnpj
        text str_email
        boolean bool_fornecedor_parceiro "default false"
        boolean bool_ativo "default false"
        date dt_de_inatividade
        string str_cep
        string str_logradouro
        string str_numero
        string str_complemento
        string str_bairro
        string str_cidade
        string str_estado
        int int_zona
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_telefones_do_fornecedor {
        int id PK
        text str_numero
        int tipo_de_telefone_id FK
        text str_observacao
        int fornecedor_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_parcerias {
        int id PK
        int unidade_id FK
        int fornecedor_id FK
        date dt_de_inicio
        date dt_de_termino
        string str_termo_de_registro
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_categorias_do_fornecedor {
        int id PK
        citext str_nome
        boolean bool_ativo "default true"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_fornecedores_categorias {
        int id PK
        int fornecedor_id FK
        int categoria_do_fornecedor_id FK
        datetime criado_em
        datetime atualizado_em
    }

    ce_fornecedores ||--o{ ce_telefones_do_fornecedor : "fornecedor tem telefones"
    ce_fornecedores ||--o{ ce_parcerias : "fornecedor tem parcerias"
    ce_fornecedores ||--o{ ce_fornecedores_categorias : "fornecedor tem categorias"
    ce_categorias_do_fornecedor ||--o{ ce_fornecedores_categorias : "categoria tem fornecedores"
    ce_parcerias }o--|| sa_unidades : "unidade_id"
    ce_telefones_do_fornecedor }o--o| sg_tipos_telefones : "tipo_de_telefone_id"
```

## 7. Cotacoes de Preco e Pedidos de Compra

```mermaid
erDiagram
    ce_cotacoes_de_preco {
        int id PK
        string str_numero
        int int_tipo_de_cotacao_de_preco
        int int_situacao_da_cotacao_de_preco
        int int_natureza_da_operacao
        date dt_da_cotacao_de_preco
        date dt_final_da_cotacao_de_preco
        int int_periodo_de_validade_da_proposta
        int ano_de_exercicio_financeiro_id FK
        int conselho_id FK
        int programa_governamental_da_unidade_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_servicos {
        int id PK
        citext str_nome
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_itens_da_cotacao_de_preco {
        int id PK
        text str_descricao
        int produto_id FK
        int servico_id FK
        int int_unidade_de_medida
        int int_quantidade
        int cotacao_de_preco_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_cotacoes_de_preco_recebidas {
        int id PK
        int fornecedor_id FK
        int cotacao_de_preco_id FK
        decimal dec_valor_total "15,2 nullable"
        boolean bool_vencedor "default false"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_itens_da_cotacao_de_preco_recebida {
        int id PK
        int item_da_cotacao_de_preco_id FK
        int cotacao_de_preco_recebida_id FK
        decimal dec_valor_unitario "15,2 nullable"
        decimal dec_valor_total "15,2 nullable"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_pedidos_de_compra {
        int id PK
        int cotacao_de_preco_recebida_id FK
        int cotacao_de_preco_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_do_pedido_de_compra {
        int id PK
        citext str_nome
        citext str_url
        citext str_path
        int pedido_de_compra_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_fornecedores_itens_da_cotacao_de_preco {
        int id PK
        int fornecedor_id FK
        int item_da_cotacao_de_preco_id FK
        datetime criado_em
        datetime atualizado_em
    }

    ce_cotacoes_de_preco ||--o{ ce_itens_da_cotacao_de_preco : "cotacao tem itens"
    ce_cotacoes_de_preco ||--o{ ce_cotacoes_de_preco_recebidas : "cotacao tem propostas"
    ce_cotacoes_de_preco ||--o{ ce_pedidos_de_compra : "cotacao tem pedidos"
    ce_cotacoes_de_preco }o--|| ce_anos_de_exercicio_financeiro : "ano_exercicio_id"
    ce_cotacoes_de_preco }o--|| ce_conselhos : "conselho_id"
    ce_cotacoes_de_preco }o--o| ce_programas_governamentais_da_unidade : "programa_gov_unidade_id"
    ce_cotacoes_de_preco_recebidas }o--|| ce_fornecedores : "fornecedor_id"
    ce_cotacoes_de_preco_recebidas ||--o{ ce_itens_da_cotacao_de_preco_recebida : "proposta tem itens"
    ce_cotacoes_de_preco_recebidas ||--o{ ce_pedidos_de_compra : "proposta gera pedido"
    ce_itens_da_cotacao_de_preco }o--o| ce_servicos : "servico_id"
    ce_itens_da_cotacao_de_preco }o--o| ae_produtos : "produto_id"
    ce_itens_da_cotacao_de_preco ||--o{ ce_itens_da_cotacao_de_preco_recebida : "item original"
    ce_itens_da_cotacao_de_preco ||--o{ ce_fornecedores_itens_da_cotacao_de_preco : "item tem fornecedores"
    ce_fornecedores ||--o{ ce_fornecedores_itens_da_cotacao_de_preco : "fornecedor tem itens"
    ce_pedidos_de_compra ||--o{ ce_anexos_do_pedido_de_compra : "pedido tem anexos"
```

## 8. Receitas

```mermaid
erDiagram
    ce_receitas {
        int id PK
        int ano_de_exercicio_financeiro_id FK
        date dt_de_competencia
        int programa_governamental_da_unidade_id FK
        int categoria_id FK
        int int_natureza_da_operacao
        int conta_bancaria_id FK
        text str_descricao
        decimal dec_valor "15,2"
        boolean bool_recurso_proprio "default false"
        boolean bool_rendimento_de_aplicacao "default false"
        int int_parcelamento
        decimal dec_valor_da_parcela "15,2"
        date dt_do_vencimento
        int int_forma_de_recebimento
        string str_numero_de_ordem_bancaria
        string str_numero_do_cheque
        string str_numero_da_parcela
        int int_intervalo_entre_parcelas
        string str_observacao
        int receita_originaria_id FK "auto-referencia"
        int bool_recebido
        date dt_de_recebimento
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_de_receita {
        int id PK
        citext str_nome
        string str_path
        string str_url
        int receita_id FK
        text str_tamanho
        text str_icone
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_historico_de_receitas {
        int id PK
        int receita_id FK
        string str_registro
        int usuario_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_receitas ||--o{ ce_receitas : "receita originaria (parcelas)"
    ce_receitas ||--o{ ce_anexos_de_receita : "receita tem anexos"
    ce_receitas ||--o{ ce_historico_de_receitas : "receita tem historico"
    ce_receitas }o--|| ce_anos_de_exercicio_financeiro : "ano_exercicio_id"
    ce_receitas }o--o| ce_programas_governamentais_da_unidade : "programa_gov_unidade_id"
    ce_receitas }o--o| ce_categorias : "categoria_id"
    ce_receitas }o--o| ce_contas_bancarias : "conta_bancaria_id"
    ce_historico_de_receitas }o--o| sg_usuarios : "usuario_id"
```

## 9. Despesas

```mermaid
erDiagram
    ce_despesas {
        int id PK
        int ano_de_exercicio_financeiro_id FK
        date dt_de_competencia
        int fornecedor_id FK
        int int_natureza_da_operacao
        int unidade_id FK
        int int_parcelamento
        decimal dec_valor_da_parcela "15,2"
        date dt_do_vencimento
        int int_intervalo_entre_parcelas
        int int_forma_de_pagamento
        string str_numero_de_ordem_bancaria
        string str_numero_do_cheque
        text str_descricao_da_nf
        decimal dec_valor_da_nf "15,2"
        string str_numero_da_nf
        date dt_de_emissao_da_nf
        string str_chave_de_acesso_da_nf
        string str_observacao
        string str_numero_da_parcela
        int int_tipo_de_servico
        int despesa_originaria_id FK "auto-referencia"
        int pedido_de_compra_id FK
        int bool_pago "default false"
        date dt_de_pagamento
        boolean bool_recurso_proprio "default false"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_itens_da_despesa {
        int id PK
        int despesa_id FK
        int programa_governamental_da_unidade_id FK
        int item_da_cotacao_de_preco_recebida_id FK
        string str_descricao
        int int_unidade_de_medida
        int int_quantidade
        decimal dec_valor_unitario "15,2"
        decimal dec_valor_total "15,2"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_condicoes_de_pagamento {
        int id PK
        int despesa_id FK
        int programa_governamental_da_unidade_id FK
        int int_parcelamento
        decimal dec_valor_da_parcela "15,2"
        datetime dt_do_vencimento
        int int_forma_de_pagamento
        string str_numero_de_ordem_bancaria
        int int_intervalo_entre_parcelas
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_de_despesa {
        int id PK
        citext str_nome
        string str_path
        string str_url
        int despesa_id FK
        text str_tamanho
        text str_icone
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_historico_de_despesas {
        int id PK
        int despesa_id FK
        string str_registro
        int usuario_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_despesas ||--o{ ce_despesas : "despesa originaria (parcelas)"
    ce_despesas ||--o{ ce_itens_da_despesa : "despesa tem itens"
    ce_despesas ||--o{ ce_condicoes_de_pagamento : "despesa tem condicoes"
    ce_despesas ||--o{ ce_anexos_de_despesa : "despesa tem anexos"
    ce_despesas ||--o{ ce_historico_de_despesas : "despesa tem historico"
    ce_despesas }o--|| ce_anos_de_exercicio_financeiro : "ano_exercicio_id"
    ce_despesas }o--o| ce_fornecedores : "fornecedor_id"
    ce_despesas }o--o| sa_unidades : "unidade_id"
    ce_despesas }o--o| ce_pedidos_de_compra : "pedido_de_compra_id"
    ce_itens_da_despesa }o--o| ce_programas_governamentais_da_unidade : "programa_gov_unidade_id"
    ce_itens_da_despesa }o--o| ce_itens_da_cotacao_de_preco_recebida : "item_cotacao_recebida_id"
    ce_condicoes_de_pagamento }o--o| ce_programas_governamentais_da_unidade : "programa_gov_unidade_id"
    ce_historico_de_despesas }o--o| sg_usuarios : "usuario_id"
```

## 10. Conciliacao Bancaria

```mermaid
erDiagram
    ce_conciliacoes_bancarias {
        int id PK
        date dt_da_conciliacao
        int unidade_id FK
        int conta_bancaria_id FK
        int ano_de_exercicio_financeiro_id FK
        int int_status
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_itens_da_conciliacao_bancaria {
        int id PK
        int conciliacao_bancaria_id FK
        date dt_da_movimentacao
        int int_tipo_de_movimentacao
        string str_descricao
        string str_numero_da_ordem_bancaria
        decimal dec_valor "15,2"
        int despesa_id FK
        int receita_id FK
        string str_numero_do_cheque
        int int_forma_de_pagamento
        int transferencia_bancaria_id FK
        int condicao_de_pagamento_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_da_conciliacao_bancaria {
        int id PK
        citext str_nome
        string str_path
        string str_url
        int conciliacao_bancaria_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_conciliacoes_bancarias ||--o{ ce_itens_da_conciliacao_bancaria : "conciliacao tem itens"
    ce_conciliacoes_bancarias ||--o{ ce_anexos_da_conciliacao_bancaria : "conciliacao tem anexos"
    ce_conciliacoes_bancarias }o--|| sa_unidades : "unidade_id"
    ce_conciliacoes_bancarias }o--|| ce_contas_bancarias : "conta_bancaria_id"
    ce_conciliacoes_bancarias }o--|| ce_anos_de_exercicio_financeiro : "ano_exercicio_id"
    ce_itens_da_conciliacao_bancaria }o--o| ce_despesas : "despesa_id"
    ce_itens_da_conciliacao_bancaria }o--o| ce_receitas : "receita_id"
    ce_itens_da_conciliacao_bancaria }o--o| ce_transferencias_bancarias : "transferencia_id"
    ce_itens_da_conciliacao_bancaria }o--o| ce_condicoes_de_pagamento : "condicao_pagamento_id"
```

## 11. Doacoes

```mermaid
erDiagram
    ce_doacoes {
        int id PK
        citext str_nome_do_doador
        string str_cpf_cnpj_do_doador
        datetime dt_da_doacao
        int programa_governamental_id FK
        int ano_de_exercicio_financeiro_id FK
        int unidade_id FK
        int conselho_id FK
        int membro_do_conselho_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_itens_da_doacao {
        int id PK
        citext str_descricao
        citext str_nota_fiscal
        int int_unidade_de_medida
        int int_quantidade
        datetime dt_nota_fiscal
        decimal dec_valor_unitario "15,2"
        decimal dec_valor_total "15,2"
        int produto_id FK
        int doacao_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_anexos_da_doacao {
        int id PK
        string str_path
        string str_url
        int doacao_id FK
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_doacoes ||--o{ ce_itens_da_doacao : "doacao tem itens"
    ce_doacoes ||--o{ ce_anexos_da_doacao : "doacao tem anexos"
    ce_doacoes }o--|| ce_programas_governamentais : "programa_gov_id"
    ce_doacoes }o--|| ce_anos_de_exercicio_financeiro : "ano_exercicio_id"
    ce_doacoes }o--|| sa_unidades : "unidade_id"
    ce_doacoes }o--|| ce_conselhos : "conselho_id"
    ce_doacoes }o--o| ce_membros_do_conselho : "membro_conselho_id"
    ce_itens_da_doacao }o--o| ae_produtos : "produto_id"
```

## 12. Plano de Aplicacao

```mermaid
erDiagram
    ce_planos_de_aplicacao {
        int id PK
        int ano_de_exercicio_financeiro_id FK
        int unidade_id FK
        int conselho_id FK
        int programa_governamental_id FK
        datetime dt_referencia
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_itens_do_plano_de_aplicacao {
        int id PK
        int plano_de_aplicacao_id FK
        string str_especificacao
        string str_objetivo
        string int_quantidade
        string int_natureza_da_operacao
        decimal dec_valor_unitario "15,2"
        decimal dec_valor_total "15,2"
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_planos_de_aplicacao ||--o{ ce_itens_do_plano_de_aplicacao : "plano tem itens"
    ce_planos_de_aplicacao }o--|| ce_anos_de_exercicio_financeiro : "ano_exercicio_id"
    ce_planos_de_aplicacao }o--|| sa_unidades : "unidade_id"
    ce_planos_de_aplicacao }o--|| ce_conselhos : "conselho_id"
    ce_planos_de_aplicacao }o--|| ce_programas_governamentais : "programa_gov_id"
```

## 13. Diretores da Unidade

```mermaid
erDiagram
    ce_diretores_da_unidade {
        int id PK
        int unidade_id FK
        int diretor_id FK
        date dt_de_posse
        date dt_de_saida
        boolean bool_diretor_atual
        datetime criado_em
        datetime atualizado_em
        datetime removido_em
    }

    ce_diretores_da_unidade }o--|| sa_unidades : "unidade_id"
    ce_diretores_da_unidade }o--|| sa_diretores : "diretor_id"
```

## 14. Visao Geral - Relacionamentos entre Entidades

```mermaid
erDiagram
    ce_bancos ||--o{ ce_contas_bancarias : ""
    ce_contas_bancarias ||--o{ ce_saldos_de_contas_bancarias : ""
    ce_contas_bancarias ||--o{ ce_movimentacoes_da_conta_bancaria : ""
    ce_contas_bancarias ||--o{ ce_transferencias_bancarias : ""
    ce_contas_bancarias ||--o{ ce_receitas : ""
    ce_contas_bancarias ||--o{ ce_conciliacoes_bancarias : ""
    ce_contas_bancarias ||--o{ ce_programas_governamentais_da_unidade : ""
    ce_programas_governamentais ||--o{ ce_programas_governamentais_da_unidade : ""
    ce_programas_governamentais ||--o{ ce_pareceres_do_conselho : ""
    ce_programas_governamentais ||--o{ ce_doacoes : ""
    ce_programas_governamentais ||--o{ ce_planos_de_aplicacao : ""
    ce_programas_governamentais_da_unidade ||--o{ ce_categorias_da_unidade : ""
    ce_programas_governamentais_da_unidade ||--o{ ce_reprogramacoes_de_recursos : ""
    ce_programas_governamentais_da_unidade ||--o{ ce_devolucoes_de_recursos : ""
    ce_programas_governamentais_da_unidade ||--o{ ce_itens_da_despesa : ""
    ce_programas_governamentais_da_unidade ||--o{ ce_condicoes_de_pagamento : ""
    ce_programas_governamentais_da_unidade ||--o{ ce_termos_de_compromisso : ""
    ce_planos_de_contas ||--o{ ce_categorias : ""
    ce_planos_de_contas ||--o{ ce_unidades_do_plano_de_contas : ""
    ce_categorias ||--o{ ce_categorias : ""
    ce_categorias ||--o{ ce_categorias_da_unidade : ""
    ce_categorias ||--o{ ce_receitas : ""
    ce_unidades_do_plano_de_contas ||--o{ ce_categorias_da_unidade : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_anos_de_exercicio_financeiro : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_receitas : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_despesas : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_cotacoes_de_preco : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_conciliacoes_bancarias : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_reprogramacoes_de_recursos : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_devolucoes_de_recursos : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_doacoes : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_saldos_de_contas_bancarias : ""
    ce_anos_de_exercicio_financeiro ||--o{ ce_planos_de_aplicacao : ""
    ce_conselhos ||--o{ ce_funcoes_do_membro_do_conselho : ""
    ce_conselhos ||--o{ ce_anexos_de_conselho : ""
    ce_conselhos ||--o{ ce_atas_de_conselho : ""
    ce_conselhos ||--o{ ce_editais_de_convocacao : ""
    ce_conselhos ||--o{ ce_cotacoes_de_preco : ""
    ce_conselhos ||--o{ ce_pareceres_do_conselho : ""
    ce_conselhos ||--o{ ce_termos_de_compromisso : ""
    ce_conselhos ||--o{ ce_doacoes : ""
    ce_conselhos ||--o{ ce_planos_de_aplicacao : ""
    ce_membros_do_conselho ||--o{ ce_funcoes_do_membro_do_conselho : ""
    ce_membros_do_conselho ||--o{ ce_doacoes : ""
    ce_modelos_de_parecer ||--o{ ce_pareceres_do_conselho : ""
    ce_pareceres_do_conselho ||--o{ ce_anexos_do_parecer : ""
    ce_fornecedores ||--o{ ce_telefones_do_fornecedor : ""
    ce_fornecedores ||--o{ ce_parcerias : ""
    ce_fornecedores ||--o{ ce_fornecedores_categorias : ""
    ce_fornecedores ||--o{ ce_fornecedores_itens_da_cotacao_de_preco : ""
    ce_fornecedores ||--o{ ce_cotacoes_de_preco_recebidas : ""
    ce_fornecedores ||--o{ ce_despesas : ""
    ce_categorias_do_fornecedor ||--o{ ce_fornecedores_categorias : ""
    ce_cotacoes_de_preco ||--o{ ce_itens_da_cotacao_de_preco : ""
    ce_cotacoes_de_preco ||--o{ ce_cotacoes_de_preco_recebidas : ""
    ce_cotacoes_de_preco ||--o{ ce_pedidos_de_compra : ""
    ce_cotacoes_de_preco_recebidas ||--o{ ce_itens_da_cotacao_de_preco_recebida : ""
    ce_cotacoes_de_preco_recebidas ||--o{ ce_pedidos_de_compra : ""
    ce_itens_da_cotacao_de_preco ||--o{ ce_itens_da_cotacao_de_preco_recebida : ""
    ce_itens_da_cotacao_de_preco ||--o{ ce_fornecedores_itens_da_cotacao_de_preco : ""
    ce_pedidos_de_compra ||--o{ ce_anexos_do_pedido_de_compra : ""
    ce_pedidos_de_compra ||--o{ ce_despesas : ""
    ce_receitas ||--o{ ce_receitas : ""
    ce_receitas ||--o{ ce_anexos_de_receita : ""
    ce_receitas ||--o{ ce_historico_de_receitas : ""
    ce_receitas ||--o{ ce_movimentacoes_da_conta_bancaria : ""
    ce_despesas ||--o{ ce_despesas : ""
    ce_despesas ||--o{ ce_itens_da_despesa : ""
    ce_despesas ||--o{ ce_condicoes_de_pagamento : ""
    ce_despesas ||--o{ ce_anexos_de_despesa : ""
    ce_despesas ||--o{ ce_historico_de_despesas : ""
    ce_despesas ||--o{ ce_movimentacoes_da_conta_bancaria : ""
    ce_conciliacoes_bancarias ||--o{ ce_itens_da_conciliacao_bancaria : ""
    ce_conciliacoes_bancarias ||--o{ ce_anexos_da_conciliacao_bancaria : ""
    ce_devolucoes_de_recursos ||--o{ ce_anexos_devolucao_de_recursos : ""
    ce_doacoes ||--o{ ce_itens_da_doacao : ""
    ce_doacoes ||--o{ ce_anexos_da_doacao : ""
    ce_planos_de_aplicacao ||--o{ ce_itens_do_plano_de_aplicacao : ""
    ce_termos_de_compromisso ||--o{ ce_anexos_do_termo_de_compromisso : ""
```

## Dependencias Externas (Cross-Module)

| FK no Caixa Escolar | Tabela Externa | Modulo |
|---|---|---|
| `ce_contas_bancarias.unidade_id` | `sa_unidades` | Academico |
| `ce_conselhos.unidade_id` | `sa_unidades` | Academico |
| `ce_parcerias.unidade_id` | `sa_unidades` | Academico |
| `ce_despesas.unidade_id` | `sa_unidades` | Academico |
| `ce_conciliacoes_bancarias.unidade_id` | `sa_unidades` | Academico |
| `ce_doacoes.unidade_id` | `sa_unidades` | Academico |
| `ce_planos_de_aplicacao.unidade_id` | `sa_unidades` | Academico |
| `ce_programas_governamentais_da_unidade.unidade_id` | `sa_unidades` | Academico |
| `ce_unidades_do_plano_de_contas.unidade_id` | `sa_unidades` | Academico |
| `ce_diretores_da_unidade.unidade_id` | `sa_unidades` | Academico |
| `ce_diretores_da_unidade.diretor_id` | `sa_diretores` | Academico |
| `ce_membros_do_conselho.pessoa_id` | `sg_pessoas` | Gerenciador |
| `ce_telefones_do_fornecedor.tipo_de_telefone_id` | `sg_tipos_telefones` | Gerenciador |
| `ce_movimentacoes_da_conta_bancaria.usuario_id` | `sg_usuarios` | Gerenciador |
| `ce_historico_de_despesas.usuario_id` | `sg_usuarios` | Gerenciador |
| `ce_historico_de_receitas.usuario_id` | `sg_usuarios` | Gerenciador |
| `ce_itens_da_cotacao_de_preco.produto_id` | `ae_produtos` | Almoxarifado |
| `ce_itens_da_doacao.produto_id` | `ae_produtos` | Almoxarifado |
