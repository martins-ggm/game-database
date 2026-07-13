# Casos de Teste — Fluxo de Login (Autenticação)

> Módulo: **Gerenciador → Autenticação**
> Objetivo: documentar o comportamento esperado da tela de login e do endpoint de
> autenticação para validação manual e em revisão de código.

---

## 1. Arquitetura do fluxo

| Camada | Arquivo | Responsabilidade |
| --- | --- | --- |
| Rota (GET) | `routes/web.php` → `gerenciador.usuario.login` | Renderiza a tela |
| Rota (POST) | `routes/web.php` → `gerenciador.usuario.autenticar` | Recebe as credenciais |
| View | `resources/views/auth/login.blade.php` | Formulário + submit AJAX (jQuery) |
| Controller | `App\Http\Controllers\Gerenciador\UsuarioController::autenticar` | Orquestra validação → autenticação → resposta |
| DTO | `App\Http\DTO\Gerenciador\UsuarioLoginDTO` | Monta e valida os dados (`email`, `password`, `lembrar`) |
| Service | `App\Services\Gerenciador\UsuarioService::autenticar` | `Auth::attempt()` + regra de negócio |
| Model | `App\Models\Gerenciador\Usuario` | Tabela `sg_usuarios`, `password` com cast `hashed` |

### Sequência (happy path)

1. Usuário acessa `GET /login`.
2. Preenche **email**, **senha** e (opcional) marca **"Lembrar de mim"**.
3. Ao submeter, o JS previne o submit padrão, limpa mensagens e envia `POST`
   AJAX para `/usuario/autenticar` com corpo JSON `{ email, password, lembrar }`
   e cabeçalhos `X-CSRF-TOKEN`, `X-Requested-With: XMLHttpRequest`, `Accept: application/json`.
4. O `UsuarioLoginDTO::fromRequest(..., bool_validar_login: true)` valida os campos.
5. `UsuarioService::autenticar` chama `Auth::attempt($credenciais, $lembrar)`.
6. Em sucesso: `session()->regenerate()` e resposta `200` com
   `{ mensagem, usuario, redirect: "/dashboard" }`.
7. O JS exibe a mensagem, reseta o form e redireciona para `response.redirect`.

---

## 2. Pré-condições

- Banco migrado e com ao menos um usuário conhecido:
  - **email:** `teste@gamedb.com`
  - **senha:** `senha-valida-123`
- `APP_DEBUG=false` no ambiente de teste (para validar o comportamento de produção
  das mensagens de erro).
- Sessão limpa (sem cookie `remember_*` residual) antes de cada caso.

---

## 3. Casos de teste

### CT-01 — Login com credenciais válidas (happy path)
- **Passos:** informar email e senha válidos, **não** marcar "Lembrar de mim", clicar em *Entrar*.
- **Esperado:**
  - `POST /usuario/autenticar` retorna **200**.
  - Corpo contém `mensagem: "Bem-vindo de volta!"`, `usuario` e `redirect: "/dashboard"`.
  - O ID de sessão é **regenerado** após a autenticação.
  - O navegador é redirecionado para `/dashboard`.

### CT-02 — "Lembrar de mim" marcado
- **Passos:** credenciais válidas **com** o checkbox marcado.
- **Esperado:**
  - Requisição envia `lembrar: true`.
  - `Auth::attempt` é chamado com `remember = true` e um cookie `remember_web_*` é criado.
  - Redireciona para `/dashboard`.

### CT-03 — "Lembrar de mim" desmarcado (controle do CT-02)
- **Passos:** credenciais válidas **sem** marcar o checkbox.
- **Esperado:**
  - Requisição envia `lembrar: false`.
  - **Nenhum** cookie `remember_web_*` é criado.
  - Redireciona para `/dashboard`.

### CT-04 — Email em branco
- **Passos:** deixar email vazio, senha qualquer, submeter.
- **Esperado:** **422** com `errors.email` = *"O e-mail é obrigatório."* — a lista `#erros` fica visível.

### CT-05 — Email com formato inválido
- **Passos:** email = `foobar` (sem `@`), senha qualquer, submeter.
- **Esperado:** **422** com `errors.email` = *"Informe um e-mail válido."*

### CT-06 — Senha em branco
- **Passos:** email válido, senha vazia, submeter.
- **Esperado:** **422** com `errors.password` = *"A senha é obrigatória."*

### CT-07 — Credenciais inválidas (senha errada ou usuário inexistente)
- **Passos:** email existente + senha incorreta (ou email inexistente).
- **Esperado:**
  - `Auth::attempt` retorna `false` → o Service lança exceção "Credenciais Inválidas".
  - Resposta **não** é 200 nem 422 (cai no ramo `else` do JS).
  - Com `APP_DEBUG=false`, a UI mostra mensagem genérica; a autenticação **não** ocorre.
  - _Observação:_ hoje isso resolve como **500** (exceção genérica). O ideal seria **401/422** — anotado como dívida técnica.

### CT-08 — CSRF ausente / inválido
- **Passos:** enviar o POST sem o cabeçalho `X-CSRF-TOKEN` válido.
- **Esperado:** **419 (Page Expired)**; nenhuma autenticação ocorre.

### CT-09 — Regeneração de sessão (proteção contra session fixation)
- **Passos:** capturar o `session id` antes do login; autenticar com sucesso.
- **Esperado:** o `session id` **após** o login é **diferente** do anterior
  (`session()->regenerate()` executado **depois** da autenticação bem-sucedida).

### CT-10 — Redirecionamento pós-login
- **Passos:** login válido.
- **Esperado:** o JS navega exatamente para o valor de `response.redirect` (`/dashboard`),
  e não para uma rota indefinida.

---

## 4. Checklist de revisão (o que um review minucioso deve confirmar)

- [ ] O seletor jQuery de cada campo corresponde ao `id` do input (`#email`, `#password`, `#lembrar`).
- [ ] `lembrar` reflete o estado real do checkbox (`.is(':checked')`), não um valor fixo.
- [ ] A regra de validação de email mantém o formato (`email`), não apenas `required`.
- [ ] `session()->regenerate()` ocorre **após** a autenticação, não antes.
- [ ] O `redirect` retornado pelo backend é o mesmo consumido pelo front (`response.redirect`).
- [ ] Credenciais inválidas não vazam detalhes internos com `APP_DEBUG=false`.
