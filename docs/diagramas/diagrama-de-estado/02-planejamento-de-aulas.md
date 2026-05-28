# Diagrama de Estado - Planejamento de Aulas

**Modulo:** Academico
**Model:** `SISP/app/Modelos/Academico/PlanejamentoDeAulas.php`
**Enum:** `SISP/app/Extras/Enums/Academico/SituacaoPlanejamentoDeAulaEnum.php`
**Campo de status:** `int_situacao` (int) — controlado via `SituacaoPlanejamentoDeAulaEnum`

> **Resumo:** Ciclo de vida do planejamento de aulas, desde a criacao pelo professor ate a validacao pelo pedagogo. O sistema suporta dois modos: sem validacao (simplificado) e com validacao (completo com aprovacao/rejeicao do pedagogo).

## Diagrama Geral (com validacao habilitada)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#4F46E5', 'primaryTextColor': '#E2E8F0', 'primaryBorderColor': '#818CF8', 'lineColor': '#94A3B8', 'secondaryColor': '#1E293B', 'tertiaryColor': '#0F172A', 'noteBkgColor': '#334155', 'noteTextColor': '#E2E8F0', 'noteBorderColor': '#64748B'}}}%%
stateDiagram-v2
    classDef alerta fill:#D97706,color:#fff,stroke:#F59E0B,stroke-width:2px
    classDef erro fill:#DC2626,color:#fff,stroke:#F87171,stroke-width:2px
    classDef sucesso fill:#16A34A,color:#fff,stroke:#4ADE80,stroke-width:2px
    classDef info fill:#2563EB,color:#fff,stroke:#60A5FA,stroke-width:2px

    [*] --> EmPlanejamento : Professor cria planejamento
    EmPlanejamento --> Concluido : Professor conclui (sem validacao)
    EmPlanejamento --> AguardandoValidacao : Professor conclui (com validacao)
    Concluido --> EmPlanejamento : Professor reabre
    AguardandoValidacao --> Aprovado : Pedagogo aprova
    AguardandoValidacao --> DevolvidoParaAjustes : Pedagogo rejeita (com observacao)
    DevolvidoParaAjustes --> AguardandoValidacao : Professor corrige e reenvia
    Aprovado --> [*]

    EmPlanejamento:::info
    Concluido:::sucesso
    AguardandoValidacao:::alerta
    Aprovado:::sucesso
    DevolvidoParaAjustes:::erro
```

## Diagrama por Modo

### Modo 1: Sem Validacao (`bool_validar_planejamento_de_aulas = false`)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#4F46E5', 'primaryTextColor': '#E2E8F0', 'primaryBorderColor': '#818CF8', 'lineColor': '#94A3B8', 'secondaryColor': '#1E293B', 'tertiaryColor': '#0F172A', 'noteBkgColor': '#334155', 'noteTextColor': '#E2E8F0', 'noteBorderColor': '#64748B'}}}%%
stateDiagram-v2
    classDef sucesso fill:#16A34A,color:#fff,stroke:#4ADE80,stroke-width:2px
    classDef info fill:#2563EB,color:#fff,stroke:#60A5FA,stroke-width:2px

    [*] --> EmPlanejamento : Professor cria planejamento
    EmPlanejamento --> Concluido : Professor conclui
    Concluido --> EmPlanejamento : Professor reabre
    Concluido --> [*]

    note right of EmPlanejamento
        Validacao desabilitada
        bool_validar_planejamento_de_aulas = false
    end note

    EmPlanejamento:::info
    Concluido:::sucesso
```

### Modo 2: Com Validacao (`bool_validar_planejamento_de_aulas = true`)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': {'primaryColor': '#4F46E5', 'primaryTextColor': '#E2E8F0', 'primaryBorderColor': '#818CF8', 'lineColor': '#94A3B8', 'secondaryColor': '#1E293B', 'tertiaryColor': '#0F172A', 'noteBkgColor': '#334155', 'noteTextColor': '#E2E8F0', 'noteBorderColor': '#64748B'}}}%%
stateDiagram-v2
    classDef alerta fill:#D97706,color:#fff,stroke:#F59E0B,stroke-width:2px
    classDef erro fill:#DC2626,color:#fff,stroke:#F87171,stroke-width:2px
    classDef sucesso fill:#16A34A,color:#fff,stroke:#4ADE80,stroke-width:2px
    classDef info fill:#2563EB,color:#fff,stroke:#60A5FA,stroke-width:2px

    [*] --> EmPlanejamento : Professor cria planejamento
    EmPlanejamento --> AguardandoValidacao : Professor conclui (marca como concluido)
    AguardandoValidacao --> Aprovado : Pedagogo aprova
    AguardandoValidacao --> DevolvidoParaAjustes : Pedagogo rejeita (com observacao)
    DevolvidoParaAjustes --> AguardandoValidacao : Professor corrige e reenvia

    note right of AguardandoValidacao
        Cria registro no historico
        Professor nao pode editar
    end note
    note right of Aprovado
        Cria registro no historico
        Edicao bloqueada permanentemente
    end note
    note right of DevolvidoParaAjustes
        Cria registro no historico
        Observacao obrigatoria
    end note

    Aprovado --> [*]

    EmPlanejamento:::info
    AguardandoValidacao:::alerta
    Aprovado:::sucesso
    DevolvidoParaAjustes:::erro
```

## Detalhamento dos Estados

| Estado | Valor (`int_situacao`) | Badge | Descricao |
|---|---|---|---|
| Em Planejamento | `1` | `badge-blue` | Estado inicial apos criacao pelo professor |
| Concluido | `2` | `badge-success` | Professor finalizou (modo sem validacao) |
| Aguardando Validacao | `3` | `badge-warning` | Aguardando analise do pedagogo |
| Aprovado | `4` | `badge-success` | Pedagogo aprovou (terminal) |
| Devolvido Para Ajustes | `5` | `badge-danger` | Pedagogo rejeitou, professor deve corrigir |

## Detalhamento das Transicoes

| De | Para | Ator | Condicao | Acao |
|---|---|---|---|---|
| `[*]` | Em Planejamento | Professor | Criar planejamento | — |
| Em Planejamento | Concluido | Professor | Validacao desabilitada (`bool_validar_planejamento_de_aulas = false`) | — |
| Em Planejamento | Aguardando Validacao | Professor | Validacao habilitada, marca como concluido | Cria registro no historico |
| Aguardando Validacao | Aprovado | Pedagogo | Analise OK | Cria registro no historico, bloqueia edicao |
| Aguardando Validacao | Devolvido Para Ajustes | Pedagogo | Analise NOK | Cria registro no historico com observacao obrigatoria |
| Devolvido Para Ajustes | Aguardando Validacao | Professor | Corrige e reenvia | Cria registro no historico |
| Concluido | Em Planejamento | Professor | Reabre para edicao | — |

## Regras de Negocio

- **Modo sem validacao:** Quando `bool_validar_planejamento_de_aulas = false`, o fluxo e simplificado (EM_PLANEJAMENTO -> CONCLUIDO), sem envolvimento do pedagogo
- **Modo com validacao:** Quando `bool_validar_planejamento_de_aulas = true`, o professor conclui e o planejamento aguarda aprovacao do pedagogo
- **Bloqueio apos aprovacao:** Estado APROVADO bloqueia completamente o planejamento (sem edicao nem remocao)
- **Bloqueio durante validacao:** Estado AGUARDANDO_VALIDACAO impede edicao pelo professor ate o pedagogo avaliar
- **Devolvido para ajustes:** Estado DEVOLVIDO_PARA_AJUSTES permite que o professor edite e reenvie para validacao
- **Observacao obrigatoria:** Ao devolver para ajustes, o pedagogo deve informar uma observacao explicando o motivo da rejeicao
- **Historico de validacoes:** Toda transicao entre AGUARDANDO_VALIDACAO, APROVADO e DEVOLVIDO_PARA_AJUSTES cria um registro no historico
