# Guidelines do Projeto SISP

> **Guia ÚNICO e COMPLETO de padrões, regras e boas práticas**
> **Consolida:** `coding-rules.md` + `coding-standards.md` + `global-rules.md`
> **Versão:** 1.0.0

---

## Índice

1. [Precedência de Documentação](#1-precedência-de-documentação)
2. [Regras Gerais de Desenvolvimento](#2-regras-gerais-de-desenvolvimento)
3. [Backend - PHP / Laravel 10](#3-backend---php--laravel-10)
4. [Frontend - Vue 3 + Tailwind CSS v3](#4-frontend---vue-3--tailwind-css-v3)
5. [Banco de Dados e Migrations](#5-banco-de-dados-e-migrations)
6. [Rotas e Permissões](#6-rotas-e-permissões)
7. [Documentação Obrigatória](#7-documentação-obrigatória)
8. [Git](#8-git)
9. [Regras para IAs](#9-regras-para-ias)

---

## 1. Precedência de Documentação

Em caso de conflito entre documentos:

```
1. guidelines.md              ← FONTE ÚNICA DE VERDADE (este arquivo)
2. Guias específicos          ← Processos detalhados (refactoring, migration, etc.)
3. README.md dos módulos      ← Documentação específica de módulos
```

| Situação | Arquivo |
|----------|---------|
| Regras gerais + padrões de código | `guidelines.md` (este arquivo) |
| Como refatorar CRUD backend | `docs/guides/backend/refactoring-guide.md` |
| Como criar/migrar frontend | `docs/guides/frontend/frontend-complete-guide.md` |
| Erros comuns | `docs/guides/troubleshooting/refactoring-errors.md` |

---

## 2. Regras Gerais de Desenvolvimento

### 2.1 Componentização e Reutilização

**Regra:** SEMPRE verificar se o componente já existe ANTES de criar.

**Processo OBRIGATÓRIO:**
```
1. PARAR - Não criar imediatamente
2. CONSULTAR docs/COMPONENT-INVENTORY.md
3. BUSCAR componentes existentes em resources/js/components/
4. SE EXISTIR → REUTILIZAR
5. SE NÃO EXISTIR → CRIAR e ATUALIZAR inventário
```

### 2.2 Proibição de alert(), confirm() e prompt() Nativos

**NUNCA** usar funções nativas do JavaScript. Usar componentes Vue:

| Situação | Componente |
|----------|------------|
| Erro | `AlertModal` (`Common/AlertModal.vue`) |
| Sucesso | `SuccessModal` (`Common/SuccessModal.vue`) |
| Confirmação | `ConfirmModal` (`Common/ConfirmModal.vue`) |

### 2.3 Requisições AJAX com Fetch API

**Regra:** TODAS as requisições `fetch()` para rotas com `ajaxMiddleware` DEVEM incluir:

```javascript
headers: {
  'X-Requested-With': 'XMLHttpRequest'
}
```

Para POST, adicionar também:
```javascript
headers: {
  'X-Requested-With': 'XMLHttpRequest',
  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
  'Content-Type': 'application/json'
}
```

Sem o header `X-Requested-With`, a requisição NEM CHEGA no controller (redireciona para `/autenticar`).

### 2.4 Segurança e Backup

**Regra:** NUNCA delete ou mova arquivos sem fazer backup antes.

```bash
# Sempre criar backup primeiro
copy arquivo.md arquivo.md.backup
# Depois de confirmar → deletar backup
```

### 2.5 CRUD de Referência

**SEMPRE** consultar o CRUD de Salas como modelo de referência:
- Backend: `app/Http/Controllers/Academico/SalaController.php`
- Service: `app/Services/Academico/SalaService.php`
- Repository: `app/Repositorios/Academico/SalaRepositorio.php`
- Frontend: `SalaList.vue`, `SalaModal.vue`

---

## 3. Backend - PHP / Laravel 10

### 3.1 PSR Standards

O projeto segue **PSR-1**, **PSR-4** e **PSR-12**.

**Nomenclatura:**

| Elemento | Padrão | Exemplo |
|----------|--------|---------|
| Classes | PascalCase | `SalaController`, `AlunoService` |
| Métodos | camelCase | `buscarPorId()`, `criarNovo()` |
| Variáveis | snake_case | `$periodo_letivo_id`, `$str_nome` |
| Constantes | UPPER_SNAKE_CASE | `MAX_CAPACIDADE` |
| Interfaces | I + PascalCase | `ISalaService`, `ISalaRepositorio` |
| DTOs | Nome + DTO | `SalaDTO`, `AlunoDTO` |
| Resources | Nome + (Contexto) + Resource | `SalaResource` (padrão), `SalaListagemResource`, `SalaSelectResource`, `SalaDetalheResource`, `SalaExportacaoResource` |

**Prefixos de tipo nas colunas:**
```php
$str_nome          // string
$int_capacidade    // integer
$float_metragem    // float
$bool_ativo        // boolean
$dt_criacao        // datetime
```

**Timestamps do projeto (português):**
```php
criado_em      // created_at
atualizado_em  // updated_at
removido_em    // deleted_at
```

**Nomes de modelos com preposições:**
```php
class NivelDeRisco extends Model {}     // ✅ "Nível de Risco"
class CategoriaDeAlerta extends Model {} // ✅ "Categoria de Alerta"
class NivelRisco extends Model {}        // ❌ ERRADO
```

### 3.2 Obrigatórios em PHP 8

```php
<?php
declare(strict_types=1); // ✅ Em Services, Repositories, Models, DTOs
// ⚠️ NÃO usar em Controllers — $request retorna strings do HTTP e a coerção automática do PHP é desejável

// ✅ Type hints e return types OBRIGATÓRIOS em TODOS os métodos (inclusive private)
public function buscarPorId(int $id): ?ISala {}

// ✅ Named arguments em TODAS as chamadas de métodos
$sala = Sala::criar(
    str_nome: $dados->str_nome,
    periodo_letivo_id: $dados->periodo_letivo_id,
);

// ✅ Named arguments inclusive em funções nativas e helpers Laravel
in_array(needle: $rota, haystack: $permissoes);
view(view: 'modulo.entidade.listar-tailwind', data: compact(['permissoes']));
response()->json(data: [...], status: 200);

// ✅ Match ao invés de switch
$result = match ($status) {
    'pending' => 'Pendente',
    'approved' => 'Aprovado',
    default => 'Desconhecido',
};

// ✅ Null safe operator
$country = $user?->address?->country;
```

### 3.3 Arquitetura em Camadas

**Estrutura de arquivos:**
```
app/
├── Http/
│   ├── Controllers/{Modulo}/{Entidade}Controller.php
│   ├── DataTransferObjects/{Modulo}/{Entidade}DTO.php
│   └── Resources/{Modulo}/{Entidade}/
│       ├── {Entidade}Resource.php              # padrão/completo (criar sempre)
│       ├── {Entidade}ListagemResource.php      # opcional — listagens paginadas
│       ├── {Entidade}DetalheResource.php       # opcional — tela de detalhe
│       ├── {Entidade}SelectResource.php        # opcional — {id, label} p/ selects
│       └── {Entidade}ExportacaoResource.php    # opcional — planilhas/relatórios
├── Services/{Modulo}/
│   ├── Interfaces/I{Entidade}Service.php
│   └── {Entidade}Service.php
├── Repositorios/{Modulo}/
│   ├── Interfaces/I{Entidade}Repositorio.php
│   └── {Entidade}Repositorio.php
└── Modelos/{Modulo}/
    ├── Interfaces/I{Entidade}.php
    └── {Entidade}.php
```

**Models DEVEM estar em `app/Modelos/{Modulo}/`** — NUNCA em `app/Models/` ou `app/Entidades/`.
**Resources DEVEM estar em `app/Http/Resources/{Modulo}/{Entidade}/`** (pasta por entidade) — ver seção **3.13 API Resources**.

### 3.4 Onde Colocar Validações

| Tipo | Camada | Exemplo |
|------|--------|---------|
| Formato de campo | **DTO** | CPF válido, campo obrigatório, tamanho |
| Unicidade | **Repository** | Nome duplicado, CPF já cadastrado |
| Regra de negócio | **Service** | Capacidade máxima, período ativo |
| Cálculo de domínio | **Model** | Metragem = largura × comprimento |

### 3.5 Controller (Thin Controller)

Controller apenas coordena. **NUNCA** tem lógica de negócio.

```php
// ✅ CORRETO — Thin Controller com named arguments
public function listar(Request $request): View
{
    $permissoes = $this->buscarPermissoesMenuInterno();
    return view(view: 'modulo.entidade.listar-tailwind', data: compact(['permissoes']));
}

public function incluir(Request $request): JsonResponse
{
    $dto = SalaDTO::fromRequest(request: $request, bool_validar_novo: true);
    $sala = $this->sala_service->criar(dados: $dto);
    return response()->json(data: ['mensagem' => 'Salvo com sucesso.', 'sala' => SalaResource::criar(collection: $sala)], status: 200);
}

public function alterar(Request $request): JsonResponse
{
    $dto = SalaDTO::fromRequest(request: $request, bool_validar_alterar: true);
    $sala = $this->sala_service->alterar(dados: $dto);
    return response()->json(data: ['mensagem' => 'Alterado com sucesso.', 'sala' => SalaResource::criar(collection: $sala)], status: 200);
}

public function remover(Request $request): JsonResponse
{
    $this->sala_service->remover(id: $request->id);
    return response()->json(data: ['mensagem' => 'Removido com sucesso.'], status: 200);
}

// ❌ ERRADO - Lógica no controller
public function incluir(Request $request)
{
    if(empty($request->str_nome)) { ... }  // ❌ Validação aqui
    $existe = DB::table('tabela')->where(...)->exists(); // ❌ Query aqui
}
```

**NUNCA no Controller:**
- Lógica de negócio (if/else com regras)
- Queries diretas (`DB::`, `Model::where()`)
- Validação inline
- Métodos duplicados (ex: `listar()` e `listarTailwind()`)
- `where()` em resultado de Paginator (Paginator NÃO tem `where()`)

**Dependency Injection:** Sempre com **Interfaces**, nunca classes concretas:
```php
public function __construct(
    protected ISalaService $sala_service,           // ✅ Interface
    protected IPeriodoLetivoService $periodo_letivo_service,  // ✅ Service auxiliar
    protected IUnidadeService $unidade_service,     // ✅ Nunca IUnidadeRepositorio
): void {}
```

**Controller NUNCA acessa Repository diretamente** — sempre via Service.
Se o Controller precisa de dados de outra entidade (ex: Unidade, PeriodoLetivo), injetar o **Service** dessa entidade (ex: `IUnidadeService`), NUNCA o Repository.

### 3.6 Service

```php
public function criar(SalaDTO $dados) : ISala
{
    $this->validarCapacidadeMaxima($dados->int_capacidade);

    return DB::transaction(function () use ($dados) {
        $sala = Sala::criar(str_nome: $dados->str_nome, ...);
        return $this->sala_repositorio->criarNovo($sala);
    });
}

private function validarCapacidadeMaxima(int $capacidade): void
{
    throw_if($capacidade > 100, new ErroValidacaoException('Capacidade máxima é 100'));
}
```

#### Tipo de retorno do Service (OBRIGATÓRIO)

O Service retorna **Model / Collection / Paginator / valor escalar de operação** — NUNCA arrays formatados para apresentação. Toda transformação entidade → resposta HTTP fica num **Resource de contexto** (seção 3.13).

| Cenário | Retorno correto |
|---------|-----------------|
| Busca 1 entidade | `?IEntidade` (Model) |
| Busca várias | `?Collection` ou `?Paginator` |
| Escrita (criar/alterar) | `IEntidade` (Model atualizado) |
| Remoção | `void` |
| Verificação | `bool` |
| Resultado de operação (inclusão em massa, validação agregada) | `array` de dados **brutos** — sem strings formatadas como `'Ilimitado'`, sem HTML, sem mensagens visuais montadas |

**Proibido no Service:**
- Montar arrays com rótulos visuais (`'Ilimitado'`, `'Sim'/'Não'`, datas formatadas em `d/m/Y`).
- Iterar relações só para achatar/reformatar saída (`foreach ($secoes as $s) { foreach ($s->campos as $c) { $arr[] = [...] } }`).
- Empacotar Collection/Paginator em `['chave' => $colecao]` apenas para conveniência do Controller.
- Chamar múltiplos métodos de contagem no Repository para popular campos de resposta — prefira `withCount`/`loadCount` no Repository e exponha os campos via Resource.

Se precisar expor subconjunto de campos, faça no Resource com `Arr::only()` ou extends (seção 3.13.4). Se a resposta depende de contagens/agregações, carregue-as via `loadCount` no Repository e o Resource lê o atributo injetado (ex: `$this->inscricoes_regulares_count`).

**Exemplo:**

```php
// ❌ ERRADO — Service formatando dados de apresentação
public function buscarResumoDaTurma(int $id): array
{
    $turma = $this->turma_repositorio->buscarPorId($id);
    return [
        'str_nome' => $turma->str_nome,
        'total_vagas' => $turma->bool_vagas_ilimitadas ? 'Ilimitado' : $turma->int_qtd_total_vagas,
        'inscricoes_regulares' => $this->inscricao_repositorio->contarPorStatus($id, REGULAR),
        'inscricoes_reserva'   => $this->inscricao_repositorio->contarPorStatus($id, RESERVA),
        'campos_personalizados' => $turma->ficha->secoes->flatMap(fn($s) => $s->campos->map(fn($c) => [...])),
    ];
}

// ✅ CORRETO — Service retorna Model com relações e counts; Resource formata
public function buscarResumoDaTurma(int $id): ITurma
{
    return $this->turma_repositorio->buscarPorIdComResumoDeInscricoes($id);
}

// No TurmaRepositorio: loadCount + load para popular atributos
// No TurmaResumoResource::toArray(): lê $this->inscricoes_regulares_count, formata 'Ilimitado', etc.
```

### 3.7 Repository

**Regra:** TODA query Eloquent (`where()`, `find()`, `get()`, `paginate()`, etc.) DEVE ser feita na camada de Repository. **NUNCA** fazer queries diretas no Controller ou Service.

```php
// ✅ CORRETO — Query no Repository
public function criarNovo(ISala $modelo) : ISala
{
    throw_if(
        $this->modelo->newQuery()->where('str_nome', $modelo->str_nome)->count(),
        new ErroValidacaoException("Sala já cadastrada")
    );
    $modelo->save();
    return $modelo;
}

// ❌ ERRADO — Query no Controller
public function listar() {
    $salas = Sala::where('bool_ativo', true)->get(); // NUNCA!
}

// ❌ ERRADO — Query no Service
public function buscar(int $id) {
    return Sala::find($id); // NUNCA! Usar $this->sala_repositorio->buscarPorId($id)
}
```

Usar `noLock()` em consultas. Suportar paginação.

### 3.8 DTO

- Estender `DTOBase` com `DTOInterface`
- Métodos: `fromRequest()`, `from()`, `todosAtributos()`
- Validação com `validarNovo()` e `validarAlterar()`
- Mensagens de erro em português

### 3.9 Model

- Estender `ModeloBase` (não `Model`)
- Implementar interface `I{Entidade}`
- Ter métodos `criar()` e `alterar()`
- Usar `Accountant` para auditoria
- Usar `$casts = []` (array, NUNCA método)
- Usar `Validador::tratarCampo()` para strings

### 3.10 Enums

```php
enum TipoEnum : int
{
    case OPCAO_1 = 1;
    case OPCAO_2 = 2;

    // Métodos OBRIGATÓRIOS:
    public function nome(): string { ... }
    public static function retornarNome(int $valor): ?string { ... }
    public static function opcoes(): array { ... }
}
```

**Model Accessor para Enums:**
```php
protected $appends = ['str_tipo'];

public function getStrTipoAttribute(): ?string
{
    return $this->int_tipo ? TipoEnum::retornarNome($this->int_tipo) : null;
}
```

### 3.11 Tratamento de Erros

```php
// ✅ Usar throw_if / throw_unless
throw_if($capacidade > 100, new ErroValidacaoException('Capacidade máxima é 100'));
throw_unless($sala, new ErroNaoEncontradoException('Sala não encontrada'));

// ❌ NÃO usar if/throw separado
```

**Namespace de exceções:** `App\Exceptions\ErroValidacaoException` (NUNCA `App\Modelos\Exceptions`).

### 3.12 Transações

Usar `DB::transaction()` em operações de escrita com múltiplas operações. NÃO usar em leituras.

### 3.13 API Resources — Organização por Contexto (OBRIGATÓRIO)

**Regra:** Resources são organizados **por contexto de uso**, nunca "por controller" nem "por rota".
O Controller **NUNCA** faz `map()`, `transform()`, `toArray()` manual ou montagem de array com dados do modelo — toda transformação entidade → resposta é feita num **Resource**.

**Simétrico ao Controller, o Service também NUNCA transforma dados para apresentação** (ver seção 3.6 — "Tipo de retorno do Service"). Se um método de Service está retornando `array` com campos formatados (strings visuais, datas `d/m/Y`, contagens agregadas), é sinal de que falta um Resource de contexto e provavelmente um método de agregação (`loadCount`/`withCount`) no Repository.

#### 3.13.1 Estrutura de Pastas

**Uma pasta por entidade, dentro do módulo:**

```
app/Http/Resources/{Modulo}/{Entidade}/
├── {Entidade}Resource.php              # padrão/completo — SEMPRE existe
├── {Entidade}ListagemResource.php      # opcional — listagens paginadas (tabelas)
├── {Entidade}DetalheResource.php       # opcional — tela de detalhe/visualização
├── {Entidade}SelectResource.php        # opcional — {id, label} p/ SearchableSelect
├── {Entidade}ExportacaoResource.php    # opcional — planilhas, PDFs, relatórios
└── {Entidade}PublicoResource.php       # opcional — API externa/portal sem auth
```

**Namespace:** `App\Http\Resources\{Modulo}\{Entidade}\{Entidade}{Contexto}Resource`

**Exemplo:**
```php
namespace App\Http\Resources\Academico\Sala;

class SalaListagemResource { ... }
```

#### 3.13.2 Contextos Padronizados

| Contexto | Sufixo | Quando usar | Conteúdo típico |
|----------|--------|-------------|-----------------|
| Padrão | `{Entidade}Resource` | Retorno após `incluir`/`alterar`/`buscarPorId` simples | Campos completos da entidade |
| Listagem | `{Entidade}ListagemResource` | Listagens paginadas (tabelas) | Enxuto: só o que a tabela exibe + `id` |
| Detalhe | `{Entidade}DetalheResource` | Tela de visualização rica | Entidade + relações carregadas |
| Select | `{Entidade}SelectResource` | Dropdowns, autocompletes | `{ value, label }` apenas |
| Exportação | `{Entidade}ExportacaoResource` | Planilha, PDF, relatórios | Formato achatado, labels humanizadas |
| Público | `{Entidade}PublicoResource` | API externa/portal sem autenticação | Subset sem campos sensíveis |

**Outros contextos** são permitidos quando o negócio exige (ex: `AlunoMatriculaResource`, `TurmaDashboardResource`), desde que o nome descreva o **contexto de uso**, não o **controller consumidor**.

#### 3.13.3 Regra de Ouro

Nomeie pelo **contexto**, nunca pelo consumidor:

- ✅ `TurmaListagemResource` — reusável por qualquer controller que liste turmas
- ❌ `TurmaParaPortalController` — acopla resource ao controller (proibido)
- ❌ `TurmaResourceV2` — versões numeradas (proibido)

#### 3.13.4 Herança entre Resources

Campos compartilhados ficam no resource padrão; contextos mais enxutos/ricos herdam e restringem/estendem:

```php
class SalaListagemResource extends SalaResource
{
    public function toArray($request): array
    {
        return Arr::only(parent::toArray($request), [
            'id', 'str_nome', 'int_capacidade', 'bool_ativo'
        ]);
    }
}
```

Assim, adicionar um campo novo no padrão se propaga automaticamente aos filhos que usam o array do pai.

#### 3.13.5 Assinatura Obrigatória do Resource

Todos os Resources do projeto DEVEM expor o método estático `criar()` para aceitar `Model`, `Collection` ou `LengthAwarePaginator`:

```php
<?php

namespace App\Http\Resources\{Modulo}\{Entidade};

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class {Entidade}Resource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'str_nome' => $this->str_nome,
            // ... demais campos
        ];
    }

    public static function criar($dados): array|JsonResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        if ($dados instanceof LengthAwarePaginator) {
            return [
                'data' => static::collection($dados->items()),
                'current_page' => $dados->currentPage(),
                'last_page' => $dados->lastPage(),
                'per_page' => $dados->perPage(),
                'total' => $dados->total(),
                'from' => $dados->firstItem(),
                'to' => $dados->lastItem(),
                'has_more_pages' => $dados->hasMorePages(),
            ];
        }

        if ($dados instanceof Collection) {
            return static::collection($dados);
        }

        return new static($dados);
    }
}
```

#### 3.13.6 Quando Criar um Novo Resource vs. Usar `when()`

**Criar novo resource de contexto quando:**
- O conjunto de campos é **claramente diferente** (ex: listagem só tem 4 campos, detalhe tem 20)
- O formato do campo muda (ex: `dt_criacao` em ISO vs. `dt_criacao` formatada "dd/mm/yyyy")
- O consumidor é estruturalmente distinto (select vs. tabela vs. exportação)

**Usar `when()` / `whenLoaded()` no mesmo resource quando:**
- A variação é de **1–2 campos condicionais**
- Depende de eager loading de uma relação:

```php
'unidade' => $this->whenLoaded('unidade', fn () => UnidadeSelectResource::criar($this->unidade)),
```

**NUNCA** duplicar resource completo por diferença pequena — isso gera `TurmaListagemResource`, `TurmaListagemResourceSemUnidade`, etc.

#### 3.13.7 Uso nos Controllers (Thin Controller)

O Controller escolhe o Resource pelo **contexto da ação**, nunca monta array manualmente:

```php
// ✅ CORRETO — Resource por contexto
public function listar(Request $request): View
{
    $permissoes = $this->buscarPermissoesMenuInterno();
    return view(view: 'academico.sala.listar-tailwind', data: compact(['permissoes']));
}

public function buscarSalas(Request $request): JsonResponse
{
    $paginado = $this->sala_service->buscar(dados: $request->all());
    return response()->json(data: ['salas' => SalaListagemResource::criar($paginado)], status: 200);
}

public function buscarParaSelect(Request $request): JsonResponse
{
    $salas = $this->sala_service->buscarTodas();
    return response()->json(data: ['salas' => SalaSelectResource::criar($salas)], status: 200);
}

public function incluir(Request $request): JsonResponse
{
    $dto = SalaDTO::fromRequest(request: $request, bool_validar_novo: true);
    $sala = $this->sala_service->criar(dados: $dto);
    return response()->json(data: ['mensagem' => 'Salvo com sucesso.', 'sala' => SalaResource::criar($sala)], status: 200);
}

public function exportar(Request $request)
{
    $salas = $this->sala_service->buscarTodas();
    return Excel::download(new SalasExport(SalaExportacaoResource::criar($salas)), 'salas.xlsx');
}
```

**❌ PROIBIDO no Controller:**
```php
// ❌ Montagem manual de array
return response()->json(['salas' => $salas->map(fn($s) => [
    'id' => $s->id,
    'nome' => $s->str_nome,
])]);

// ❌ transform() no controller
$salas->transform(function ($sala) { ... });
```

Se for tentado escrever isso num Controller, **criar um Resource de contexto apropriado** e delegar a transformação.

#### 3.13.8 Migração de Resource Monolítico (Legado)

Ao encontrar um Resource antigo de formato unificado:

1. Criar pasta `app/Http/Resources/{Modulo}/{Entidade}/` (se não existe)
2. Mover `{Entidade}Resource.php` para dentro (ajustar namespace)
3. Identificar os `map()`/`transform()` espalhados nos Controllers que consomem essa entidade
4. Para cada variação, criar o Resource de contexto (`Listagem`, `Select`, etc.) herdando do padrão
5. Substituir no Controller pela chamada ao Resource de contexto correspondente
6. Atualizar `use` statements em Controllers, Services de exportação e testes

---

## 4. Frontend - Vue 3 + Tailwind CSS v3

### 4.1 Nomenclatura JS/Vue

```javascript
// Componentes: PascalCase
export default { name: 'SalaList' }

// Variáveis/funções: camelCase
const userName = 'João';
function calculateTotal(items) { ... }

// Constantes: UPPER_SNAKE_CASE
const MAX_ITEMS = 100;
```

### 4.2 Boas Práticas JS

```javascript
// ✅ const/let, NUNCA var
// ✅ Arrow functions
// ✅ Destructuring
// ✅ Spread operator
// ✅ Template literals
// ✅ Optional chaining: user?.profile?.name
// ✅ Nullish coalescing: userName ?? 'Anônimo'
// ✅ async/await
```

### 4.3 Componentes Vue - Composition API

Usar `setup(props)` com `ref`, `computed`, `onMounted`.

**Componentes obrigatórios de `Common/`:**
- `PageHeader`, `FilterAccordion`, `DataTable`, `Pagination`
- `ModernButton`, `InputField`, `FormField`, `NumericInput`
- `SearchableSelect` (OBRIGATÓRIO — NUNCA `<select>` HTML)
- `UserSearchInput` (busca assíncrona de usuários — usar em vez de SearchableSelect para campos com muitos registros)
- `SuccessModal`, `ConfirmModal`, `AlertModal`, `ErrorModal`, `ValidationModal`
- `LoadingBackdrop`, `SwitchToggle`, `Badge`, `Card`
- Form: `DatePickerField`, `FormGrid`, `MultiSelectField`, `TextareaField`
- Form: `SecoesPerguntasBuilder` (builder de seções e perguntas)

### 4.4 SearchableSelect é OBRIGATÓRIO

```vue
<!-- ❌ NUNCA -->
<select v-model="form.campo"><option>...</option></select>

<!-- ✅ SEMPRE -->
<searchable-select v-model="form.campo" :options="items" label-key="label" value-key="value" />
```

### 4.5 FormField + InputField

**Todo campo de formulário (POST/PUT) DEVE ser envolvido por `FormField`:**

```vue
<!-- ✅ CORRETO -->
<FormField label="Nome" :required="true" :error="errors.nome">
  <InputField v-model="form.nome" :error="!!errors.nome" />
</FormField>

<!-- ❌ ERRADO - InputField sozinho em formulário -->
<InputField v-model="form.nome" label="Nome" :error="errors.nome" />
```

**Campos de busca/filtro NÃO precisam de FormField.**

### 4.6 Computed "Todos" em Filtros

```javascript
const tiposComTodos = computed(() => [
    { value: '', label: 'Todos' },
    ...tipos.value
]);
```

### 4.7 Cor do Módulo em Componentes

Repassar `moduleColor` da View Blade → List → Modal → Componentes:

```blade
<sala-list module-color="{{ $modulo_atual->str_cor_primaria ?? '#3B82F6' }}" />
```

### 4.8 View Blade + Vue 3

```blade
{{-- ✅ CORRETO --}}
@extends('layouts.tailwind')
@section('app-id', 'app-entidade')
@section('content')
    <entidade-list :permissions="{{ json_encode($permissoes) }}" />
@endsection
@push('scripts')
    @vite('resources/js/apps/{modulo}/app-{modulo}-{entidade}-tailwind.js')
@endpush
```

**Regras CRÍTICAS:**
- `@section('app-id')` — NUNCA `<div id="app-x">`
- `{{ json_encode() }}` — NUNCA `@json()` em props Vue
- `@push('scripts')` — NUNCA `@section('scripts')`

### 4.9 Entry Point

**Arquivo:** `resources/js/apps/{modulo}/app-{modulo}-{entidade}-tailwind.js`

**Imports OBRIGATÓRIOS (sem eles = tela sem topbar/sidebar):**
```javascript
import TopbarFull from '../../components/Layout/TopbarFull.vue';
import Sidebar from '../../components/Layout/Sidebar.vue';
import SearchModal from '../../components/Modals/SearchModal.vue';
import AppFooter from '../../components/Layout/AppFooter.vue';
```

### 4.10 Responsividade (CRÍTICO)

**SEMPRE considerar responsividade.** Breakpoints Tailwind:
- Base: Mobile (< 640px)
- `sm:` 640px | `md:` 768px | `lg:` 1024px | `xl:` 1280px | `2xl:` 1536px

**Padrões:**
```vue
<!-- Grid responsivo -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

<!-- Tabela responsiva: desktop tabela, mobile cards -->
<div class="hidden md:block"><table>...</table></div>
<div class="md:hidden space-y-4"><card v-for="..." /></div>

<!-- Modal responsivo -->
<div class="fixed inset-0 md:inset-auto w-full md:w-auto md:max-w-2xl">

<!-- Botões: stack mobile, inline desktop -->
<div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
```

**Usar `gap-*` para espaçamento** — NUNCA margin entre itens flex/grid.
**Botões min 44x44px** para toque.
**Testar em:** 375px (iPhone SE), 768px (iPad), 1920px (Desktop).

### 4.11 Compilação de Assets

**O projeto usa dois sistemas de bundling:**

| Sistema | Escopo | Comando |
|---------|--------|---------|
| **Vite** | Telas novas (Vue 3 + Tailwind) | `npm run dev` / `npm run build` |
| **Legacy** | Telas antigas (jQuery/Blade) | `npm run build:legacy` |

**Comandos:**
```bash
npm run dev          # Vite dev server com HMR (OBRIGATÓRIO durante dev frontend)
npm run build        # Build de produção (Vite)
npm run build:legacy # Build dos bundles legados (jQuery)
npm run build:all    # Ambos (legacy + Vite)
```

**Telas novas (Vue 3)** usam `@vite()`:
- Entry points em `resources/js/apps/` são coletados automaticamente pelo `vite.config.js`
- NÃO precisa registrar manualmente — basta criar o arquivo no diretório correto

**Telas antigas (jQuery)** usam `legacy_asset()`:
- Helper em `app/Helpers/helpers.php` que lê `public/legacy-manifest.json`
- Bundles gerados por `scripts/build-legacy-bundles.js`
- Usar para views que ainda dependem de `app.min.js`/`app.min.css`

**SEMPRE** rodar `npm run dev` antes de trabalhar com views/componentes Vue/CSS.

### 4.12 Estrutura de Apps Vue 3

```
resources/js/
├── apps/{modulo}/app-{modulo}-{entidade}-tailwind.js  # Entry points por módulo
├── components/{Modulo}/{Entidade}List.vue     # Componentes por módulo
├── composables/                               # Lógica compartilhada
├── utils/                                     # Utilitários
└── stores/                                    # State management
```

### 4.13 Card para Containers com Darkmode

**Usar `Card` ao invés de divs manuais** para containers que precisam de darkmode:

```vue
<!-- ❌ EVITAR -->
<div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700 p-4">

<!-- ✅ PREFERIR -->
<Card padding="p-4" rounded="rounded-lg" shadow="shadow-md" custom-class="text-center">
  Conteúdo aqui
</Card>
```

**Props:** `padding`, `rounded`, `shadow`, `border` (Boolean), `customClass`.
**Slots:** `default`, `#header`, `#footer`.

### 4.14 UserSearchInput para Busca de Usuários

**Usar em campos que buscam usuários** com muitos registros (busca assíncrona com debounce):

```vue
<user-search-input
  v-model="nomeUsuario"
  :search-url="routes.buscarUsuarios"
  search-param="nome"
  placeholder="Buscar por nome..."
  :min-chars="2"
  :debounce-ms="400"
  @select="handleUserSelect"
/>
```

**Quando usar:** campos de responsável, palestrante, ou qualquer busca de usuário.
**NÃO usar SearchableSelect** carregando todos os usuários na memória.

---

## 5. Banco de Dados e Migrations

### 5.1 Banco é Apenas Armazenamento

**PROIBIDO no banco:** UNIQUE constraints, CHECK, Triggers, Stored Procedures, Functions.
**PERMITIDO:** Primary Keys, Foreign Keys, Indexes, Timestamps, Tipos de dados.

Toda validação e lógica fica no código (DTO, Service, Repository).

### 5.2 NUNCA usar `onDelete('cascade')`

```php
// ❌ ERRADO
$table->foreign('regiao_id')->references('id')->on('ba_regioes')->onDelete('cascade');

// ✅ CORRETO - Sem cascade, gerenciar no Service
$table->foreign('regiao_id')->references('id')->on('ba_regioes');
```

### 5.3 Campos de Texto

**Usar `text()` sem tamanho**, exceto quando explicitamente requerido (CEP, CPF, cor hex):

```php
$table->text('str_nome');           // ✅ Sem limitação
$table->string('str_cpf', 11);     // ✅ Formato fixo conhecido
```

### 5.4 Nomenclatura de Tabelas

- Prefixo por módulo: `sa_` (Acadêmico), `sg_` (Gerenciador), `ba_` (Busca Ativa)
- Timestamps: `criado_em`, `atualizado_em`, `removido_em`
- Prefixos de tipo: `str_`, `int_`, `bool_`, `float_`, `dt_`

### 5.5 Migrations de Task

**NUNCA** criar migrations manualmente. Usar:
```bash
cd SISP && php artisan gerarSeederTask {sprint} {task}
```

O comando gera automaticamente:
- Seeder em `database/seeders/Updates/Sprints/SPRINTXXX/SeederSISPYYYY.php`
- Migration em `database/migrations/updates/Sprints/SPRINTXXX/`

---

## 6. Rotas e Permissões

### 6.1 Formato OBRIGATÓRIO: camelCase

```
nomeDoModulo.nomeDaFuncionalidade.permissaoDeAcesso
```

```php
// ✅ CORRETO
'buscaAtiva.nivelDeRisco.listar'

// ❌ ERRADO
'busca-ativa.nivel-de-risco.listar'  // dash-case
'busca_ativa.nivel_de_risco.listar'  // snake_case
```

### 6.2 Regra de Ouro

```
Seeder (str_rota) = Rota (->name()) = Controller (possuiPermissao)
```

Os 3 DEVEM usar EXATAMENTE o mesmo nome.

### 6.3 CRUD Padrão (4 Rotas)

```php
Route::get('/sala/listar', [SalaController::class, 'listar'])->name('academico.sala.listar');
Route::post('/sala/incluir', [SalaController::class, 'incluir'])->name('academico.sala.incluir');
Route::post('/sala/alterar', [SalaController::class, 'alterar'])->name('academico.sala.alterar');
Route::post('/sala/remover', [SalaController::class, 'remover'])->name('academico.sala.remover');
```

### 6.4 Rotas Auxiliares com Hífen

Tudo após o hífen é **ignorado** na verificação de permissões:

```php
// Herda permissão de "listar"
Route::get('/sala/buscar', [SalaController::class, 'buscarSalas'])
    ->name('academico.sala.listar-buscarSalas');
```

### 6.5 Sub-CRUDs (Cadastros Aninhados)

Quando um cadastro filho é gerenciado dentro do pai, usar rotas com hífen para herdar permissões:

```php
// Herda permissão de incluir do PAI
Route::post('/categoria-de-alerta/regra/incluir', '...@incluirRegra')
    ->name('buscaAtiva.categoriaDeAlerta.incluir-regra');
```

### 6.6 Métodos HTTP

| Método | Uso |
|--------|-----|
| **GET** | Leitura: `listar`, `buscar*`, `visualizar`, `exportar` |
| **POST** | Escrita: `incluir`, `alterar`, `remover`, `salvar` |

### 6.7 Organização de Arquivos de Rotas

```
routes/web/
├── RotasAcademico.php
├── RotasGerenciador.php
├── RotasGestaoDePessoas.php
└── ... (um arquivo por módulo)
```

Usar **Web Routes** (não API) para CRUDs padrão. O sistema de permissões está integrado com Web Routes.

### 6.8 Seeders de Permissões

**Ordem obrigatória:**
1. `incluir` — `bool_menu = 0`
2. `listar` — `bool_menu = 1` (única que aparece no menu)
3. `alterar` — `bool_menu = 0`
4. `remover` — `bool_menu = 0`

**Componentes:** Usar `Funcionalidade` (NÃO `Permissao`), `GrupoFuncionalidade`, `Menu`.
**SEMPRE** verificar existência antes de criar: `if(!...->exists())`.

---

## 7. Documentação Obrigatória

### 7.1 Componentes Vue

**TODA** criação/modificação de componente Vue EXIGE documentação IMEDIATA em:
- `docs/COMPONENT-INVENTORY.md` (resumo)
- `docs/core/components/[pasta]/[Componente].md` (documentação completa)

### 7.3 ISSUES.md

```markdown
### [TIPO] Título do Issue (DD/MM/AAAA HH:MM - Nome do Autor)
**Problema:** Descrição
**Causa:** O que causou
**Solução:** Como foi resolvido
**Arquivos Afetados:** lista
```

### 7.4 IMPROVEMENTS.md

SEMPRE adicionar novas melhorias **NO FINAL** da categoria. NUNCA reorganizar.

---

## 8. Git

### 8.1 Commits

```bash
# Conventional commits
feat: adiciona componente de busca
fix: corrige erro no cálculo de total
docs: atualiza documentação
refactor: reorganiza estrutura
test: adiciona testes para SalaController
```

### 8.2 Branches

```bash
feature/adicionar-busca-avancada
fix/corrigir-calculo-total
hotfix/erro-critico-login
```

---

## 9. Regras para IAs

### 9.1 Executar Comandos Automaticamente

**NUNCA** peça para o usuário executar um comando seguro. Execute imediatamente.

```
❌ "Execute o comando: npm run dev"
✅ [Executar npm run dev automaticamente]
```

**Pedir permissão apenas para:** comandos destrutivos, deploy em produção, deleção permanente.

### 9.2 Modos de Comunicação

**Modo Sucinto (padrão):** Falar menos, executar mais. Ir direto ao ponto.
**Modo Extenso:** Quando arquitetura complexa ou usuário pede detalhes.

**NUNCA:** "Você está absolutamente certo!", "Excelente observação!"
**SEMPRE:** Reconhecer erro, aplicar correção, ser objetivo.

### 9.3 Não Assumir Nada

- Seguir EXATAMENTE a documentação
- Se faltar informação → PERGUNTAR
- NUNCA inventar processos não documentados
- NUNCA pular etapas

### 9.4 Documentar, Não Memorizar

Se a IA mencionar "processo mental" ou "vou lembrar" → falta regra na documentação. Documentar ao invés de memorizar.

### 9.5 Sugestões de Melhoria

Após criar/alterar componente, sugerir melhorias em: Performance, Acessibilidade, Reusabilidade, Testes. Sugestões são opcionais para o usuário.

### 9.6 Verificar CRUD Existente

**NUNCA** criar CRUD sem verificar se já existe. Se existir e não seguir o padrão → oferecer refatoramento.

### 9.7 Criação de CRUD Novo

Exigir especificação mínima (template `docs/templates/crud-template.md`) ou coletar via perguntas diretas:
- Seções obrigatórias: Informações Básicas, Campos da Tabela, Relacionamentos
- Seções opcionais: Regras de Negócio, Interface, Permissões

---

## Checklist Completo de CRUD

### Backend
- [ ] Controller "fino" (thin controller)
- [ ] DTO com validações (`validarNovo`, `validarAlterar`)
- [ ] Service com lógica de negócio e `DB::transaction`
- [ ] Repository com queries e validação de unicidade
- [ ] Model com `criar()`, `alterar()`, Accessors para Enums
- [ ] Resources em `Resources/{Modulo}/{Entidade}/` (padrão + contextos usados: Listagem, Select, etc.)
- [ ] Controller sem `map()`/`transform()`/array manual — toda transformação vai pro Resource de contexto
- [ ] Interfaces em todas as camadas (sincronizadas com TODOS os métodos públicos)
- [ ] Bindings nos Service Providers
- [ ] Controller NÃO injeta Repositories (apenas Services)
- [ ] Todos os métodos com return type explícito
- [ ] Named arguments em todas as chamadas de métodos

### Frontend
- [ ] View Blade (`listar-tailwind.blade.php`)
- [ ] `{Entidade}List.vue` com componentes Common
- [ ] `{Entidade}Modal.vue` com validação
- [ ] Entry point com TODOS os imports de layout
- [ ] Entry point em resources/js/apps/ (Vite coleta automaticamente)
- [ ] Responsivo (mobile, tablet, desktop)

### Rotas e Permissões
- [ ] 4 rotas padrão (listar, incluir, alterar, remover)
- [ ] Nomenclatura camelCase
- [ ] Seeder = Rota = Controller (mesmo nome)
- [ ] Rotas em `routes/web/{Modulo}.php`

### Documentação
- [ ] COMPONENT-INVENTORY.md atualizado
- [ ] CHANGELOG/fragmento atualizado
- [ ] Componentes documentados individualmente

---

**Mantido por:** Equipe SISP 2020
**Consolida:** `coding-rules.md` (depreciado) + `coding-standards.md` + `global-rules.md`
