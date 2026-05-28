# ER - Relacionamentos Cross-Module

Mapa de todas as foreign keys que cruzam entre os 3 modulos principais.

---

## Gerenciador para Academico

```mermaid
graph LR
    subgraph Gerenciador["Gerenciador - sg_*"]
        P[sg_pessoas]
        U[sg_usuarios]
    end

    subgraph Academico["Academico - sa_*"]
        AL[sa_alunos]
        PR[sa_professores]
        PE[sa_professores_especialistas]
        DI[sa_diretores]
        SE[sa_secretarios]
        CO[sa_coordenadores_de_ensino]
        IN[sa_inspetores_escolares]
        PD[sa_pedagogos]
        MO[sa_monitores]
        AX[sa_auxiliares_de_secretaria]
        GE[sa_gestores]
        UN[sa_unidades]
        EN[sa_enturmacoes]
        RS[sa_responsaveis]
    end

    P -->|pessoa_id| AL
    P -->|pessoa_id| PR
    P -->|pessoa_id| PE
    P -->|pessoa_id| DI
    P -->|pessoa_id| SE
    P -->|pessoa_id| CO
    P -->|pessoa_id| IN
    P -->|pessoa_id| PD
    P -->|pessoa_id| MO
    P -->|pessoa_id| AX
    P -->|pessoa_id| GE
    P -->|pessoa_id| RS
    U -->|diretor_id| UN
    U -->|secretario_id| UN
    U -->|usuario_remocao_id| EN

    style Gerenciador fill:#263238,stroke:#42A5F5,color:#E3F2FD,stroke-width:2px
    style Academico fill:#1a2e1a,stroke:#66BB6A,color:#E8F5E9,stroke-width:2px
```

---

## Gerenciador para Pre-Matricula

```mermaid
graph LR
    subgraph Gerenciador["Gerenciador - sg_*"]
        P[sg_pessoas]
        U[sg_usuarios]
    end

    subgraph PreMatricula["Pre-Matricula - pm_*"]
        PMA[pm_alunos]
        MOV[pm_movimentacoes]
        ME[pm_movimentacoes_enturmacoes]
        AN[pm_anotacoes]
        AA[pm_anotacoes_alunos]
        MS[pm_mensagens_sms]
        UU[pm_unidades_usuarios]
        SC[pm_solicitacoes_de_classificacao]
        RR[pm_registros via usuario_id]
    end

    P -->|pessoa_id| PMA
    U -->|usuario_id| MOV
    U -->|usuario_id| ME
    U -->|usuario_id| AN
    U -->|usuario_id| AA
    U -->|usuario_id| MS
    U -->|usuario_id| UU
    U -->|usuario_id| SC

    style Gerenciador fill:#263238,stroke:#42A5F5,color:#E3F2FD,stroke-width:2px
    style PreMatricula fill:#2e1a00,stroke:#FFA726,color:#FFF3E0,stroke-width:2px
```

---

## Academico para Pre-Matricula

```mermaid
graph LR
    subgraph Academico["Academico - sa_*"]
        PL[sa_periodos_letivos]
        CU[sa_cursos]
        UNA[sa_unidades]
        TU[sa_turmas]
        TR[sa_turnos]
        AE[sa_anos_de_escolaridade]
    end

    subgraph PreMatricula["Pre-Matricula - pm_*"]
        PRO[pm_processos]
        CUA[pm_cursos_academicos]
        PMU[pm_unidades]
        PMT[pm_turmas]
        PMTR[pm_turnos]
        PMAE[pm_anos_de_escolaridade]
    end

    PL -->|periodo_letivo_academico_id| PRO
    CU -->|curso_modulo_academico_id| CUA
    UNA -->|unidade_academico_id| PMU
    TU -->|turma_academico_id| PMT
    TR -->|turno_academico_id| PMTR
    AE -->|ano_de_escolaridade_academico_id| PMAE

    style Academico fill:#1a2e1a,stroke:#66BB6A,color:#E8F5E9,stroke-width:2px
    style PreMatricula fill:#2e1a00,stroke:#FFA726,color:#FFF3E0,stroke-width:2px
```

---

## Tabela Resumo de FKs Cross-Module

| Origem | FK | Destino | Descricao |
|---|---|---|---|
| **sg_pessoas** | `pessoa_id` | sa_alunos | Dados pessoais do aluno |
| **sg_pessoas** | `pessoa_id` | sa_professores | Dados pessoais do professor |
| **sg_pessoas** | `pessoa_id` | sa_professores_especialistas | Professor especialista |
| **sg_pessoas** | `pessoa_id` | sa_diretores | Diretor |
| **sg_pessoas** | `pessoa_id` | sa_secretarios | Secretario |
| **sg_pessoas** | `pessoa_id` | sa_coordenadores_de_ensino | Coordenador |
| **sg_pessoas** | `pessoa_id` | sa_inspetores_escolares | Inspetor |
| **sg_pessoas** | `pessoa_id` | sa_pedagogos | Pedagogo |
| **sg_pessoas** | `pessoa_id` | sa_monitores | Monitor |
| **sg_pessoas** | `pessoa_id` | sa_auxiliares_de_secretaria | Auxiliar |
| **sg_pessoas** | `pessoa_id` | sa_gestores | Gestor |
| **sg_pessoas** | `pessoa_id` | sa_responsaveis | Responsavel do aluno |
| **sg_pessoas** | `pessoa_id` | pm_alunos | Aluno pre-matricula |
| **sg_usuarios** | `diretor_id` | sa_unidades | Diretor da unidade |
| **sg_usuarios** | `secretario_id` | sa_unidades | Secretario da unidade |
| **sg_usuarios** | `usuario_remocao_id` | sa_enturmacoes | Quem removeu enturmacao |
| **sg_usuarios** | `usuario_id` | pm_movimentacoes | Quem movimentou inscricao |
| **sg_usuarios** | `usuario_id` | pm_unidades_usuarios | Acesso a unidade |
| **sa_periodos_letivos** | `periodo_letivo_academico_id` | pm_processos | Periodo do processo |
| **sa_cursos** | `curso_modulo_academico_id` | pm_cursos_academicos | Curso espelhado |
| **sa_unidades** | `unidade_academico_id` | pm_unidades | Unidade espelhada |
| **sa_turmas** | `turma_academico_id` | pm_turmas | Turma espelhada |
| **sa_turnos** | `turno_academico_id` | pm_turnos | Turno espelhado |
| **sa_anos_de_escolaridade** | `ano_escolaridade_academico_id` | pm_anos_de_escolaridade | Ano espelhado |
