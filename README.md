# 🎮 Game Database (GAMEDB)

Catálogo interativo de jogos — uma **biblioteca onde o usuário cataloga, descobre e organiza** seu universo gamer: monta sua coleção, avalia jogos e acompanha o que está em alta.

> Projeto-sandbox construído para praticar a **arquitetura em camadas SISP** (Controller → DTO → Service → Repositório → Model → Resource) sobre Laravel 12. O foco aqui é tanto o produto quanto a **organização do código**.

---

## 🧱 Arquitetura SISP

O coração do projeto. Cada requisição atravessa uma pilha de camadas com responsabilidades bem separadas, e a comunicação entre elas é feita por **interfaces** (nunca por classes concretas), resolvidas por **injeção de dependência**.

```
HTTP Request
    │
    ▼
Controller ──▶ DTO            (recebe a Request, monta/normaliza os dados de entrada)
    │
    ▼
Service                       (regra de negócio, orquestra transações — DB::transaction)
    │
    ▼
Repositório                   (acesso a dados: queries Eloquent)
    │
    ▼
Model                         (entidade: relações, casts, soft delete, criar()/editar())
    │
    ▼
Resource                      (formata a saída)
    │
    ▼
JSON  /  View (Blade)
```

| Camada | Responsabilidade |
|---|---|
| **Controller** | Recebe a `Request`, monta o **DTO**, chama o **Service** e devolve `View` ou `JsonResponse`. Não contém regra de negócio. |
| **DTO** | Objeto de transporte dos dados de entrada (`fromRequest()`), com validação/normalização. |
| **Service** | Regra de negócio. Orquestra transações (`DB::transaction`) e combina um ou mais repositórios. Depende de **interfaces**. |
| **Repositório** | Único ponto que fala com o banco (Eloquent). Implementa uma interface. |
| **Model** | Entidade Eloquent: relações, `$casts`, soft delete e fábricas (`criar()` / `editar()`). |
| **Resource** | Formata a resposta. O `criar()` trata **item único, coleção e paginador** (`LengthAwarePaginator`) de forma transparente. |

**Interfaces + DI:** cada Service e Repositório expõe uma interface (`IJogoService`, `IJogoRepositorio`…). O [`AppServiceProvider`](app/Providers/AppServiceProvider.php) amarra `Interface → implementação`, e a injeção de dependência entrega tudo pronto no construtor. Trocar a implementação = trocar uma linha no provider.

---

## 🛠️ Stack

- **[Laravel 12](https://laravel.com)** · PHP 8.2+
- **PostgreSQL** (usa recursos específicos, ex.: `ilike`)
- **[Intervention Image](https://image.intervention.io/)** — processamento de upload (redimensiona + converte para `webp`)
- **Blade** + **Tailwind CSS v3 (via CDN)** — visual _MetroUI flat_, paleta lavanda (Espurr)
- **jQuery** (telas autenticadas) e **JavaScript vanilla** (telas públicas) para as interações AJAX
- **[Laravel Herd](https://herd.laravel.com)** recomendado para servir localmente (nginx + PHP-FPM → requisições em paralelo)

> ℹ️ Tailwind e jQuery são carregados por **CDN** — não há pipeline Vite (`@vite`) nas views. Por isso **não é necessário `npm run build`/`npm run dev`** para rodar o projeto. O plugin do Tailwind v4 no editor pode acusar classes "canônicas" diferentes; são **falsos-positivos** (o projeto usa v3).

---

## 📁 Estrutura (organizada por domínio + camada)

```
app/
├── Http/
│   ├── Controllers/   ← Catalogo · Colecao · Gerenciador
│   ├── DTO/           ← Catalogo · Review · Gerenciador
│   ├── Resources/     ← formatação da saída (JSON)
│   └── Middleware/    ← VerificarPermissao (rota admin)
├── Services/          ← Catalogo · Colecao · Review · Gerenciador · Imagem  (+ Interfaces/)
├── Repositorios/      ← Catalogo · Colecao · Review · Gerenciador           (+ Interfaces/)
├── Models/            ← Catalogo · Colecao · Review · Gerenciador
├── View/Components/   ← componentes Blade de classe (ex.: Footer)
├── Helpers/           ← permissoes.php (helper global, autoloaded)
└── Providers/         ← AppServiceProvider (bindings de DI)

routes/                ← web.php (orquestra) + um arquivo por entidade + telas.php
resources/views/       ← Blade, com components/ compartilhados (navbar, footer)
database/              ← migrations + seeders
```

### Domínios

| Domínio | Entidades | O que cobre |
|---|---|---|
| **Catalogo** | Jogo, Gênero, Plataforma, Desenvolvedora | Catálogo, cadastro admin, "em alta" |
| **Colecao** | Coleção _(pivô 1ª classe jogo↔usuário)_, Situação | Biblioteca pessoal com status (jogando, na lista, dropado…) |
| **Review** | Review | Avaliações (nota + comentário) por jogo |
| **Gerenciador** | Usuário, PatchNote _(+ Perfil/Permissão dormentes)_ | Autenticação, acesso admin, home e changelog |

---

## ✨ Funcionalidades

- **Catálogo** de jogos organizado por gênero, com uma seção **"em alta"** (gêneros/jogos mais avaliados nos últimos 30 dias).
- **Coleção pessoal**: adicione jogos à sua biblioteca com uma **situação**, e filtre por ela no perfil.
- **Reviews**: nota + comentário na tela do jogo, com **média** e foto/nome do autor.
- **Home**: destaques "em alta", notícias e **patch notes** (changelog paginado; a versão mais recente aparece no rodapé).
- **CRUD admin** de jogos, gêneros, plataformas e desenvolvedoras — com **paginação** (AJAX) e busca.
- **Autenticação** e **área admin** protegida por middleware (`permissao`), controlada por uma flag `admin` no usuário. _(Um modelo completo de perfis/permissões existe no banco, porém dormente.)_
- **Upload de imagens** otimizado: redimensionado e convertido para `webp` no processamento.

---

## 🚀 Como rodar

### Pré-requisitos
- PHP **8.2+** e **Composer**
- **PostgreSQL**
- **[Laravel Herd](https://herd.laravel.com)** (recomendado) — ou `php artisan serve`

### Passos

```bash
# 1. Dependências PHP
composer install

# 2. Ambiente
cp .env.example .env
php artisan key:generate
# edite o .env: DB_CONNECTION=pgsql + credenciais do seu Postgres

# 3. Banco (estrutura + dados de exemplo)
php artisan migrate --seed

# 4. Link do storage (necessário para servir as imagens em /storage)
php artisan storage:link
```

### Servindo

**Com Herd (recomendado):** aponte o Herd para a pasta e acesse `http://game-database.test`.
```bash
herd park     # dentro da pasta que contém seus projetos (cada subpasta vira <nome>.test)
# ou
herd link     # dentro deste projeto
```

**Ou com o servidor embutido:**
```bash
php artisan serve   # http://127.0.0.1:8000
```

> 💡 O Herd usa nginx + PHP-FPM e atende requisições **em paralelo** — as imagens carregam de imediato. O `php artisan serve` é single-thread e serializa as requisições (as capas "pipocam" uma a uma).

### Seeders
`DatabaseSeeder` popula Plataformas, Desenvolvedoras, Gêneros, Jogos, Situações e Patch Notes. Há ainda um `ReviewTesteSeeder` (não registrado no `DatabaseSeeder`) para gerar usuários e reviews de teste em massa:
```bash
php artisan db:seed --class=ReviewTesteSeeder
```

---

## 📐 Convenções do projeto

- **Nomenclatura em português** nas camadas (`Repositorios`, `Servicos`, `criar`, `editar`, `buscar…`).
- **Soft delete** com coluna customizada **`removido_em`** (`const DELETED_AT = 'removido_em'` nos models).
- **Uma interface por Service/Repositório**, com o bind no `AppServiceProvider`.
- **Rotas separadas por entidade** (`jogos.php`, `generos.php`…) mais `telas.php` (apenas rotas que devolvem view), orquestradas pelo `web.php`.
- **DTOs** para toda entrada de dados relevante (`fromRequest()`).

> 📚 **Fontes de verdade internas:** [`docs/guidelines.md`](docs/guidelines.md) para a arquitetura/backend (camadas, rotas, validação, naming) e [`docs/estilo-visual.md`](docs/estilo-visual.md) para a identidade visual. Em caso de conflito, os docs mandam.

---

## 🔖 Commits e versionamento

- **Mensagens de commit em português**, descritivas (ex.: _"Paginação nas listagens e sistema de patch notes"_). Este projeto usa esse estilo em vez de _conventional commits_ — divergência consciente do [`docs/guidelines.md`](docs/guidelines.md) §8, adequada ao ritmo de um sandbox.
- **Patch notes = changelog vivo.** Mudanças significativas (feature nova, comportamento visível) geram uma **nova versão** (semver):
  - _feature_ → bump **minor** (`0.5.0` → `0.6.0`)
  - _correção_ → bump **patch** (`0.5.0` → `0.5.1`)
  - interno/trivial (refactor, doc) → **sem** entrada
- A versão nova é registrada em [`database/seeders/PatchNoteSeeder.php`](database/seeders/PatchNoteSeeder.php) (fonte versionada) e refletida na tabela `patch_notes` — aparecendo no changelog da home e no rodapé.

---

<sub>Construído sobre o [Laravel](https://laravel.com) · projeto de estudo da arquitetura SISP.</sub>
