# 01 - Arquitetura Geral do SISP

## 1.1 Diagrama de Contexto

Visao de alto nivel: atores, sistema SISP e servicos externos.

```mermaid
graph TB
    subgraph Atores["Atores / Usuarios"]
        AL(("Aluno<br/>Estudante da<br/>rede de ensino"))
        PR(("Professor<br/>Docente vinculado<br/>a turmas"))
        GE(("Gestor Escolar<br/>Diretor, secretario<br/>ou coordenador"))
        RE(("Responsavel<br/>Pai, mae ou<br/>responsavel legal"))
        AD(("Administrador<br/>Admin do sistema<br/>e permissoes"))
    end

    SISP["<b>SISP</b><br/>Sistema Integrado de<br/>Gestao Escolar<br/>Gerencia matriculas,<br/>turmas, notas,<br/>frequencia, transporte,<br/>biblioteca<br/>e 24+ modulos educacionais"]

    subgraph Externos["Servicos Externos"]
        S3["AWS S3<br/>Arquivos e documentos"]
        ES["Elasticsearch<br/>Busca full-text"]
        GC["Google Calendar<br/>Calendario academico"]
        SP["SparkPost<br/>Envio de e-mails"]
        SOAP["Sistema Legado<br/>Integracao SOAP/XML"]
    end

    AL -->|"Consulta notas e frequencia<br/>(HTTPS / App Mobile)"| SISP
    PR -->|"Lanca notas e frequencia<br/>(HTTPS / App Mobile)"| SISP
    GE -->|"Administra unidade escolar<br/>(HTTPS)"| SISP
    RE -->|"Pre-matricula e acompanha aluno<br/>(HTTPS)"| SISP
    AD -->|"Configura modulos e permissoes<br/>(HTTPS)"| SISP

    SISP -->|"AWS SDK"| S3
    SISP -->|"REST API"| ES
    SISP -->|"Google API"| GC
    SISP -->|"SMTP/API"| SP
    SISP -->|"SOAP/XML"| SOAP

    style SISP fill:#1565C0,stroke:#0D47A1,color:#fff,stroke-width:3px
    style Atores fill:#263238,stroke:#42A5F5,color:#E3F2FD,stroke-width:2px
    style Externos fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5,stroke-width:2px
    style AL fill:#263238,stroke:#42A5F5,color:#E3F2FD
    style PR fill:#263238,stroke:#42A5F5,color:#E3F2FD
    style GE fill:#263238,stroke:#42A5F5,color:#E3F2FD
    style RE fill:#263238,stroke:#42A5F5,color:#E3F2FD
    style AD fill:#263238,stroke:#42A5F5,color:#E3F2FD
    style S3 fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
    style ES fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
    style GC fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
    style SP fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
    style SOAP fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
```

## 1.2 Diagrama de Containers

Componentes internos do SISP e suas interacoes.

```mermaid
graph TB
    U(("Usuario<br/>Aluno, Professor,<br/>Gestor ou Admin"))
    M(("Usuario Mobile<br/>Aluno ou Professor<br/>via App"))

    subgraph SISP["SISP - Sistema Integrado de Gestao Escolar"]
        subgraph Apresentacao["Camada de Apresentacao"]
            SPA["<b>Frontend Vue 3</b><br/>Vue 3, Tailwind CSS<br/>162 componentes por modulo"]
            BLADE["<b>Views Blade</b><br/>Laravel Blade<br/>Templates server-side,<br/>layouts e slots Vue"]
        end

        subgraph Aplicacao["Camada de Aplicacao"]
            LARAVEL["<b>Backend Laravel</b><br/>Laravel 10, PHP 8.2<br/>24+ modulos: Controllers,<br/>Services e Repositories"]
            APIREST["<b>API REST</b><br/>Passport OAuth2<br/>6 controllers API,<br/>autenticacao por token"]
            QUEUE["<b>Queue Worker</b><br/>Laravel Queue<br/>Jobs assincronos: emails,<br/>relatorios, calculos"]
        end

        subgraph DadosLayer["Camada de Dados"]
            PG[("<b>PostgreSQL</b><br/>Banco principal<br/>Prefixos: sg_* sa_* pm_*<br/>174 migrations")]
            CACHE[("<b>Cache</b><br/>Redis / File<br/>Permissoes 1h TTL,<br/>sessoes, dados frequentes")]
        end
    end

    subgraph Ext["Servicos Externos"]
        S3["AWS S3<br/>Armazenamento"]
        ELASTIC["Elasticsearch<br/>Busca full-text"]
        MAIL["SparkPost<br/>E-mails"]
    end

    U -->|"Acessa via navegador (HTTPS)"| BLADE
    U -->|"Interage com componentes"| SPA
    M -->|"HTTPS + Bearer Token"| APIREST

    BLADE -->|"Renderiza componentes Vue"| SPA
    SPA -->|"Requisicoes AJAX (Axios)"| LARAVEL
    BLADE -->|"Server-side rendering (PHP)"| LARAVEL
    APIREST -->|"Processa requests API"| LARAVEL

    LARAVEL -->|"Eloquent ORM / PDO"| PG
    LARAVEL -->|"Redis/File Driver"| CACHE
    LARAVEL -->|"Despacha jobs"| QUEUE
    QUEUE -->|"Eloquent ORM"| PG

    LARAVEL -->|"AWS SDK"| S3
    LARAVEL -->|"REST Client"| ELASTIC
    LARAVEL -->|"SMTP"| MAIL

    style SISP fill:#1a1a2e,stroke:#42A5F5,color:#E3F2FD,stroke-width:3px
    style Apresentacao fill:#2e1a00,stroke:#FFA726,color:#FFF3E0,stroke-width:2px
    style Aplicacao fill:#1a2e1a,stroke:#66BB6A,color:#E8F5E9,stroke-width:2px
    style DadosLayer fill:#2e1a1a,stroke:#EF5350,color:#FFEBEE,stroke-width:2px
    style Ext fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5,stroke-width:2px
    style U fill:#263238,stroke:#42A5F5,color:#E3F2FD
    style M fill:#263238,stroke:#42A5F5,color:#E3F2FD
    style LARAVEL fill:#1a2e1a,stroke:#66BB6A,color:#E8F5E9
    style APIREST fill:#1a2e1a,stroke:#66BB6A,color:#E8F5E9
    style QUEUE fill:#1a2e1a,stroke:#66BB6A,color:#E8F5E9
    style SPA fill:#2e1a00,stroke:#FFA726,color:#FFF3E0
    style BLADE fill:#2e1a00,stroke:#FFA726,color:#FFF3E0
    style PG fill:#2e1a1a,stroke:#EF5350,color:#FFEBEE
    style CACHE fill:#2e1a1a,stroke:#EF5350,color:#FFEBEE
    style S3 fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
    style ELASTIC fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
    style MAIL fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
```

## 1.3 Visao Compacta

Versao resumida com todas as camadas.

```mermaid
graph TB
    subgraph Usuarios["Usuarios"]
        A1[Aluno]
        A2[Professor]
        A3[Gestor Escolar]
        A4[Responsavel]
        A5[Administrador]
    end

    subgraph Mobile["Apps Mobile"]
        M1[App Aluno]
        M2[App Professor]
    end

    subgraph SISP["SISP - Sistema Integrado de Gestao Escolar"]
        subgraph Frontend["Camada de Apresentacao"]
            FE1[Vue 3 + Tailwind CSS<br/>162 componentes]
            FE2[Blade Templates<br/>25+ modulos de views]
        end

        subgraph Backend["Camada de Aplicacao"]
            BE1[Laravel 10 - PHP 8.2<br/>24+ modulos]
            BE2[API REST<br/>Passport OAuth2]
            BE3[Queue Worker<br/>Jobs assincronos]
        end

        subgraph Dados["Camada de Dados"]
            DB1[(PostgreSQL<br/>sg_* sa_* pm_*)]
            DB2[(Cache<br/>Redis / File)]
        end
    end

    subgraph Externos["Servicos Externos"]
        EX1[AWS S3 - Arquivos]
        EX2[Elasticsearch - Busca]
        EX3[SparkPost - E-mail]
        EX4[Google Calendar]
        EX5[SOAP - Legado]
    end

    A1 & A2 & A3 & A4 & A5 -->|HTTPS| FE1 & FE2
    M1 & M2 -->|HTTPS + Bearer Token| BE2
    FE1 -->|Axios| BE1
    FE2 -->|PHP| BE1
    BE2 --> BE1
    BE1 --> DB1
    BE1 --> DB2
    BE1 --> BE3
    BE3 --> DB1
    BE1 --> EX1 & EX2 & EX3 & EX4 & EX5

    style SISP fill:#1a1a2e,stroke:#42A5F5,color:#E3F2FD,stroke-width:2px
    style Frontend fill:#2e1a00,stroke:#FFA726,color:#FFF3E0
    style Backend fill:#1a2e1a,stroke:#66BB6A,color:#E8F5E9
    style Dados fill:#2e1a1a,stroke:#EF5350,color:#FFEBEE
    style Externos fill:#1a1a2e,stroke:#AB47BC,color:#F3E5F5
    style Usuarios fill:#263238,stroke:#42A5F5,color:#E3F2FD
    style Mobile fill:#263238,stroke:#42A5F5,color:#E3F2FD
```
