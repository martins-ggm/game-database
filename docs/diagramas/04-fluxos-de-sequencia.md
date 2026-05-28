# 04 - Diagramas de Sequencia (Fluxos Criticos)

## 4.1 Fluxo de Autenticacao

### 4.1a Autenticacao Web (Session)

```mermaid
sequenceDiagram
    actor U as Usuario
    participant B as Browser
    participant LC as LoginController
    participant Auth as AuthenticatesUsers Trait
    participant MW as Middleware Web Group
    participant DB as sg_usuarios / sg_pessoas

    U->>B: Acessa /login
    B->>LC: GET /login
    LC-->>B: Formulario de login (Blade view)

    U->>B: Preenche login e senha
    B->>MW: POST /login
    MW->>MW: EncryptCookies - StartSession - VerifyCsrfToken

    MW->>LC: Request validada
    LC->>Auth: login(Request)
    Auth->>DB: SELECT * FROM sg_usuarios WHERE str_login = ? AND str_senha = ?

    alt Credenciais validas
        DB-->>Auth: Usuario encontrado
        Auth->>Auth: Auth::login($usuario) - Cria Session
        Auth-->>LC: Autenticado
        LC-->>B: Redirect /home
    else Credenciais invalidas
        DB-->>Auth: null
        Auth-->>LC: ThrottleException / Falha
        LC-->>B: Redirect /login com erro
    end
```

### 4.1b Autenticacao API (Passport OAuth2)

```mermaid
sequenceDiagram
    actor U as App Mobile
    participant API as AuthController API
    participant Crypt as Crypt decryptString
    participant Repo as UsuarioRepositorio
    participant DB as sg_usuarios
    participant Passport as Laravel Passport

    U->>API: POST /api/login {login encrypted, senha encrypted}

    API->>Crypt: decryptString(login)
    Crypt-->>API: login descriptografado
    API->>Crypt: decryptString(senha)
    Crypt-->>API: senha descriptografada

    API->>Repo: buscarUsuarioPorLoginESenha(login, senha)
    Repo->>DB: SELECT * FROM sg_usuarios WHERE str_login = ? AND str_senha = ?

    alt Usuario encontrado
        DB-->>Repo: Usuario
        Repo-->>API: Usuario

        API->>API: Verifica bool_acesso_api == true

        alt Acesso API permitido
            API->>Passport: auth user createToken authToken
            Passport-->>API: accessToken + expires_at
            API-->>U: 200 {access_token, expires_at, usuario}
        else Acesso API negado
            API-->>U: 401 Usuario sem permissao de acesso a API
        end
    else Usuario nao encontrado
        DB-->>Repo: null
        Repo-->>API: null
        API-->>U: 401 Credenciais invalidas
    end
```

### 4.1c Autenticacao App Mobile (Aluno/Professor)

```mermaid
sequenceDiagram
    actor U as App Mobile
    participant API as AuthController
    participant Repo as UsuarioRepositorio
    participant DB as Database
    participant Passport as Laravel Passport

    U->>API: POST /api/app/auth {login, senha}

    API->>Repo: buscarUsuarioPorLoginESenha(login, senha)
    Repo->>DB: SELECT sg_usuarios + sg_pessoas
    DB-->>Repo: Usuario com Pessoa

    alt Usuario encontrado
        Repo-->>API: Usuario

        API->>DB: usuario.pessoa.alunoAcademico exists
        Note over API,DB: Verifica se e aluno (sa_alunos)

        alt Nao e aluno
            API->>DB: usuario.pessoa.responsavelModuloAcademico exists
            Note over API,DB: Verifica se e responsavel
        end

        alt Nao e responsavel
            API->>DB: usuario.pessoa.professorModuloAcademico exists
            Note over API,DB: Verifica se e professor (sa_professores)
        end

        alt E aluno, responsavel ou professor
            API->>Passport: createToken authToken
            Passport-->>API: Token + expires_at
            API-->>U: 200 {access_token, tipo_usuario, expires_at}
        else Nenhum perfil mobile
            API-->>U: 403 Perfil nao permitido para app
        end
    else Nao encontrado
        Repo-->>API: null
        API-->>U: 401 Credenciais invalidas
    end
```

### 4.1d Pipeline de Middleware (Acesso a Rotas Protegidas)

```mermaid
sequenceDiagram
    actor U as Usuario Autenticado
    participant MW1 as manutencaoMiddleware
    participant MW2 as acessoRestritoMiddleware
    participant MW3 as permissaoAcessoMiddleware
    participant Cache as Cache 1h TTL
    participant DB as Database
    participant C as Controller

    U->>MW1: GET /academico/enturmacao/listar

    MW1->>MW1: Verifica manutencao do modulo
    alt Em manutencao
        MW1-->>U: 503 Sistema em manutencao
    end

    MW1->>MW2: Request
    MW2->>MW2: Auth check
    alt Nao autenticado
        MW2-->>U: Redirect /login UsuarioNaoAutenticadoException
    end
    MW2->>MW2: $usuario bool_bloqueado?
    alt Bloqueado
        MW2-->>U: 403 UsuarioBloqueadoException
    end
    MW2->>DB: Funcionalidade where str_rota = $rota
    DB-->>MW2: Funcionalidade + Modulo
    MW2->>MW2: Session put modulo_id $modulo id

    MW2->>MW3: Request
    MW3->>Cache: Cache remember permissoes_usuario_$id 3600

    alt Cache miss
        Cache->>DB: Query 1: sg_usuarios_perfis - sg_perfis_funcionalidades WHERE bool_acesso = true
        DB-->>Cache: $permissoes_perfis
        Cache->>DB: Query 2: sg_perfis_personalizados WHERE bool_acesso = true
        DB-->>Cache: $permissoes_personalizadas
        Cache->>DB: Query 3: sg_perfis_personalizados WHERE bool_acesso = false
        DB-->>Cache: $bloqueios
        Cache->>Cache: Resultado = perfis + personalizadas - bloqueios
    end

    Cache-->>MW3: Lista de rotas permitidas

    MW3->>MW3: Verifica se rota atual esta na lista
    alt Sem permissao
        MW3-->>U: 403 PermissaoNegadaException
    end

    MW3->>C: Request autorizada
    C-->>U: Response 200
```

---

## 4.2 Fluxo de Matricula / Inscricao - Passos 0 a 2

```mermaid
sequenceDiagram
    actor R as Responsavel
    participant B as Browser
    participant IC as InscricaoController
    participant Repo as Repositorios PM
    participant RepoAcad as Repositorios Academico
    participant DB as Database
    participant S as Session

    Note over R,S: PASSO 0 - Iniciar Inscricao

    R->>B: Seleciona dependente
    B->>IC: GET /gestao-de-vagas/iniciar-inscricao/{dependente_id}
    IC->>Repo: Validar processo ativo + sem manutencao
    Repo->>DB: pm_processos WHERE bool_manutencao = false
    DB-->>Repo: Processo ativo
    IC->>S: Session put dependente, processo, configuracao
    IC-->>B: Redirect para inscricao

    Note over R,S: PASSO 1 - Confirmar Periodo e Validar Dados

    R->>B: Preenche CPF, certidao, ano de escolaridade
    B->>IC: POST /gestao-de-vagas/inscricao/validar-passo1

    IC->>IC: Validador validarCPF $cpf
    IC->>Repo: buscarPorCpf $cpf
    Repo->>DB: SELECT sg_pessoas WHERE str_cpf = ?
    Note over IC: Verifica CPF nao duplicado

    IC->>IC: Validar idade vs ano de escolaridade

    IC->>RepoAcad: buscarPorNomePorDataDeNascimento
    RepoAcad->>DB: SELECT sa_alunos + sg_pessoas WHERE nome AND dt_nascimento
    Note over IC: Verifica se aluno ja e da rede

    IC->>RepoAcad: verificarSeExisteEnturmacaoRegularOuPendente
    RepoAcad->>DB: SELECT sa_enturmacoes WHERE aluno_id AND periodo_letivo_id AND int_situacao IN 1,2
    Note over IC: Verifica se ja esta enturmado

    IC->>Repo: verificarSeJaExisteInscricaoParaOutroCpf
    Note over IC: Previne inscricao duplicada

    IC->>S: Session put str_cpf, bool_aluno_da_rede
    IC-->>B: Avanca para passo 2

    Note over R,S: PASSO 2 - Dados do Responsavel

    R->>B: Preenche nome, CPF, telefone, email
    B->>IC: POST /gestao-de-vagas/inscricao/validar-passo2
    IC->>IC: Valida dados do responsavel
    IC->>S: Session put dados_responsavel
    IC-->>B: Avanca para passo 3
```

## 4.2b Fluxo de Matricula / Inscricao - Passos 3 a 5

```mermaid
sequenceDiagram
    actor R as Responsavel
    participant B as Browser
    participant IC as InscricaoController
    participant Repo as Repositorios PM
    participant DB as Database
    participant ES as EnturmacaoService Academico
    participant S as Session

    Note over R,S: PASSO 3 - Selecao de Unidades e Turnos

    R->>B: Seleciona opcoes de unidade prioridade 1, 2, 3
    B->>IC: GET /gestao-de-vagas/inscricao/consultar/unidades
    IC->>Repo: buscar unidades com vagas por curso/ano
    Repo->>DB: SELECT pm_turmas WHERE int_quantidade_vagas_disponiveis > 0
    DB-->>Repo: Unidades com vagas
    Repo-->>IC: Lista de unidades
    IC-->>B: Exibe unidades disponiveis

    B->>IC: POST /gestao-de-vagas/inscricao/validar-passo3
    IC->>S: Session put turnos, opcoes_de_unidades
    IC-->>B: Avanca para passo 4

    Note over R,S: PASSO 4 - Documentos

    R->>B: Anexa documentos obrigatorios
    B->>IC: POST /gestao-de-vagas/inscricao/validar-passo4
    IC->>IC: Valida documentos requeridos
    IC-->>B: Avanca para passo 5

    Note over R,S: PASSO 5 - Confirmacao e Salvamento

    R->>B: Confirma inscricao
    B->>IC: POST /gestao-de-vagas/inscricao/novo/salvar-inscricao

    IC->>DB: DB transaction INICIO

    alt Cadastro Reserva bool_cadastro_reserva = true
        IC->>Repo: Criar inscricao com status CADASTRO_RESERVA
    else Convocacao Direta
        IC->>Repo: Criar inscricao com status CONVOCADO
    end

    Repo->>DB: INSERT pm_inscricoes

    loop Para cada opcao de unidade prioridade 1, 2, 3
        IC->>Repo: Criar pm_inscricoes_unidades
        Repo->>DB: INSERT pm_inscricoes_unidades
    end

    alt Necessidade especial
        IC->>Repo: buscarTurmaComVagasNecessidadeEspecial
    else Normal
        IC->>Repo: buscarComVagasEDisponibilidade
    end
    Repo->>DB: SELECT pm_turmas WHERE vagas > 0
    DB-->>Repo: Turma disponivel

    alt Integracao com Academico ativa
        IC->>ES: novaEnturmacao aluno_id, turma_id, dt_matricula, status
        ES->>DB: INSERT sa_enturmacoes
        ES->>DB: INSERT sa_fichas_de_disciplinas para cada disciplina da grade
        DB-->>ES: Enturmacao criada
        ES-->>IC: Sucesso
    end

    IC->>DB: DB transaction COMMIT

    IC->>Repo: Criar pm_movimentacoes registro de auditoria
    Repo->>DB: INSERT pm_movimentacoes

    IC-->>B: Comprovante de inscricao PDF
    B-->>R: Exibe comprovante
```

---

## 4.3 Fluxo de Lancamento de Notas - Etapas 1 e 2

```mermaid
sequenceDiagram
    actor P as Professor
    participant B as Browser
    participant AC as AvaliacaoController
    participant Repo as AvaliacaoRepositorio
    participant DB as Database

    Note over P,DB: ETAPA 1 - Listar Avaliacoes

    P->>B: Acessa modulo de avaliacoes
    B->>AC: GET /academico/avaliacao/listar
    AC->>AC: Recupera filtros: periodo_letivo, etapa, unidade, curso, turma, disciplina

    AC->>Repo: buscarPorPeriodoLetivoPorEtapaPorTurma
    Repo->>DB: SELECT sa_avaliacoes WHERE periodo_letivo_id AND etapa_id AND turma_id AND disciplina_id
    DB-->>Repo: Lista de avaliacoes
    Repo-->>AC: Collection de Avaliacao
    AC-->>B: View com tabela de avaliacoes

    Note over P,DB: ETAPA 2 - Criar Nova Avaliacao

    P->>B: Clica em Nova Avaliacao
    B->>AC: GET /academico/avaliacao/novo/{curso}/{etapa}/{unidade}/{periodo}/{ano}/{turma}/{disciplina}

    AC->>AC: buscarPontosDistribuidos
    AC->>Repo: Soma pontos ja distribuidos na etapa
    Repo->>DB: SELECT SUM int_pontos FROM sa_avaliacoes WHERE etapa_id AND disciplina_id AND turma_id
    DB-->>Repo: Total de pontos usados
    Note over AC: Valida: total + nova <= limite da etapa

    AC-->>B: Formulario de avaliacao nome, pontos, tipo

    P->>B: Preenche dados da avaliacao
    B->>AC: POST /academico/avaliacao/incluir
    AC->>Repo: Criar avaliacao
    Repo->>DB: INSERT sa_avaliacoes str_nome, int_pontos, int_tipo_avaliacao, etapa_id, disciplina_id, turma_id
    DB-->>Repo: Avaliacao criada
    AC-->>B: Sucesso - redirect para lista
```

## 4.3b Fluxo de Lancamento de Notas - Etapas 3 e 4

```mermaid
sequenceDiagram
    actor P as Professor
    participant B as Browser
    participant AC as AvaliacaoController
    participant Repo as AvaliacaoRepositorio
    participant FDRepo as FichaDeDisciplinaRepositorio
    participant Calc as CalculoDeNotasService
    participant DB as Database

    Note over P,DB: ETAPA 3 - Lancar Notas dos Alunos

    P->>B: Seleciona avaliacao para lancar notas
    B->>AC: GET /academico/avaliacao/alterar/{id}

    AC->>Repo: Buscar avaliacao com notas
    Repo->>DB: SELECT sa_avaliacoes + sa_notas_avaliacoes
    DB-->>Repo: Avaliacao com notas existentes

    AC->>FDRepo: Buscar fichas de disciplinas da turma
    FDRepo->>DB: SELECT sa_fichas_de_disciplinas JOIN sa_enturmacoes JOIN sa_alunos JOIN sg_pessoas
    DB-->>FDRepo: Lista de alunos com fichas

    AC-->>B: Formulario com lista de alunos e campos de nota

    P->>B: Preenche notas para cada aluno
    B->>AC: POST /academico/avaliacao/alterar/salvar

    loop Para cada aluno da turma
        AC->>Repo: Salvar/Atualizar nota
        Repo->>DB: INSERT/UPDATE sa_notas_avaliacoes float_nota, avaliacao_id, ficha_de_disciplina_id
    end

    AC-->>B: Notas salvas com sucesso

    Note over P,DB: ETAPA 4 - Calculo de Nota Final

    P->>B: Solicita calculo de notas
    B->>AC: Aciona calculo

    AC->>Calc: calcularNotaFinal turma_id, disciplina_id

    loop Para cada aluno ficha_de_disciplina
        Calc->>DB: SELECT sa_notas_avaliacoes WHERE ficha_de_disciplina_id GROUP BY etapa_id
        DB-->>Calc: Notas por etapa

        Calc->>Calc: Calcula media por etapa soma notas / total pontos
        Calc->>Calc: Calcula media final media das etapas

        alt Tem recuperacao
            Calc->>DB: SELECT nota recuperacao WHERE int_tipo_avaliacao = recuperacao
            DB-->>Calc: Nota de recuperacao
            Calc->>Calc: Aplica regra de recuperacao substitui se maior
        end

        Calc->>DB: SELECT sa_frequencias WHERE ficha_de_disciplina_id
        DB-->>Calc: Total de faltas
        Calc->>Calc: Calcula percentual frequencia

        Calc->>DB: UPDATE sa_fichas_de_disciplinas SET float_nota_final, int_frequencia, int_situacao
        Note over Calc,DB: int_situacao: APROVADO / REPROVADO / EM_RECUPERACAO
    end

    Calc-->>AC: Calculo concluido
    AC-->>B: Resultado do calculo quantidade aprovados/reprovados
    B-->>P: Exibe resultado
```

## 4.4 Legenda de Status

### Status de Enturmacao (sa_enturmacoes.int_situacao)
| Codigo | Status |
|--------|--------|
| 1 | REGULAR (matriculado) |
| 2 | PENDENTE (condicional) |
| 3 | CANCELADA |
| 4 | TRANSFERIDA |

### Status de Inscricao (pm_inscricoes.status_id)
| Codigo | Status |
|--------|--------|
| 1 | CADASTRO_RESERVA (lista de espera) |
| 2 | CONVOCADO (chamado para matricula) |
| 3 | MATRICULADO |
| 4 | CANCELADO |

### Tipos de Avaliacao (sa_avaliacoes.int_tipo_avaliacao)
| Codigo | Tipo |
|--------|------|
| 1 | Avaliacao Regular |
| 2 | Recuperacao Paralela |
| 3 | Avaliacao Integrada |
| 4 | Recuperacao de Etapa |
| 5 | Recuperacao Geral |
