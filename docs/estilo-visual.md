# Estilo Visual — Game Database

> **Escopo:** este documento define **APENAS a identidade visual** deste projeto.
> **Toda a arquitetura de código** (Controllers, Services, Repositories, DTOs, Resources, rotas, permissões, validação, nomenclatura, organização de pastas, padrões PHP) segue rigorosamente o [`guidelines.md`](guidelines.md) — o "jeito SISP".
> Em caso de conflito entre este arquivo e o `guidelines.md`, **o `guidelines.md` prevalece**.

---

## 1. Identidade

| Campo | Valor |
|---|---|
| **Nome do projeto** | Game Database |
| **Marca curta** | GAMEDB |
| **Tipo** | Biblioteca interativa de jogos |
| **Inspiração visual** | Site da [NIS America](https://nisamerica.com) — estética **MetroUI**: dark, flat, geométrica, tiles que se tocam, acento mono-cromático |
| **Mascote** | Espurr (Pokémon) — silhueta lavanda no footer de todas as páginas. Arquivo: [`public/misc/espurr.svg`](../public/misc/espurr.svg) |

A vibe é **vitrine de console / catálogo digital**: fundo quase preto com leve tom lavanda, blocos retangulares, acento violeta único, tipografia condensada uppercase. Nada de ornamento — densidade de informação primeiro. A identidade ganhou um **mascote** (Espurr — Pokémon), presente como silhueta lavanda no footer de todas as páginas.

---

## 2. Regras absolutas do estilo

Estas regras são **invioláveis** nas telas que adotam o estilo de marketing/vitrine. Pra cada item há um "porquê" curto, pra você saber quando uma exceção faria sentido (raramente).

| Regra | Por quê |
|---|---|
| ✅ **Dark obrigatório** (`#11101A` ou mais escuro) | Identidade da marca, contraste pro acento violeta funcionar |
| ❌ **ZERO gradientes** (`bg-gradient-to-*`, gradientes em texto) | MetroUI é cores chapadas; gradiente quebra a linguagem |
| ❌ **Sem cantos arredondados** (sem `rounded-*`, máximo `rounded-sm` em casos raros) | Tile retangular é o DNA do MetroUI |
| ❌ **Sem sombras decorativas / glow / blur** (`shadow-*` colorido, `blur-*`, glow effects) | A intenção é "papel digital chapado", não vidro/neon |
| ❌ **Sem animações exuberantes** (`float`, `pulse-glow`, scale-grande) | Movimento sutil; tile só muda a cor de fundo no hover |
| ✅ **Acento mono-cromático** — apenas **um** violeta `#6B5B9E` em todo o sistema visual | Hierarquia clara; tudo que é interativo/destaque usa essa cor |
| ✅ **Tiles que se tocam ou quase** (`gap-1` ou `gap-px`) | Layout de catálogo MetroUI clássico |
| ✅ **Uppercase + tracking-widest** em headings e CTAs | Voz da marca; passa "etiqueta de inventário" |

---

## 3. Paleta de cores

### Base (estritamente)
| Função | Hex | Tailwind |
|---|---|---|
| Fundo da página | `#11101A` | `bg-[#11101A]` |
| Superfície (tile) | `#1C1B26` | `bg-[#1C1B26]` |
| Superfície em hover | `#25232F` | `bg-[#25232F]` |
| Borda sutil | branco a 10% | `border-white/10` |
| Borda visível | branco a 30% | `border-white/30` |
| Texto principal | branco | `text-white` |
| Texto secundário | branco a 60% | `text-white/60` |
| Texto sutil | branco a 40% | `text-white/40` |
| Texto muito sutil | branco a 15% | `text-white/15` |

### Acento — único
| Função | Hex | Tailwind |
|---|---|---|
| **Acento (todos os usos)** | `#6B5B9E` | `bg-[#6B5B9E]` / `text-[#6B5B9E]` / `border-[#6B5B9E]` |
| Acento hover (sutilmente mais claro) | `#8674B8` | `bg-[#8674B8]` |

O acento é o **violeta da pupila do Espurr** — escolha deliberada para casar com o mascote.

Não existe acento secundário. **Tudo** que é destaque, link em hover, badge, número de stat, borda focada → usa `#6B5B9E`. Se você sentir vontade de adicionar uma segunda cor de acento, **pare e reconsidere**: provavelmente é falta de hierarquia, não falta de cor.

### Cor do mascote (uso exclusivo do Espurr)
| Função | Hex | Tailwind |
|---|---|---|
| Silhueta do Espurr (corpo) | `#C6C2D9` | `bg-[#C6C2D9]` |

**Não usar** `#C6C2D9` fora do mascote. Não é acento, não é texto, não é borda — é a cor do pelo do Espurr e só.

### Estados de formulário (formulários internos)
| Estado | Fundo | Borda | Texto |
|---|---|---|---|
| Sucesso | `bg-emerald-50` | `border-emerald-200` | `text-emerald-700` |
| Erro | `bg-red-50` | `border-red-200` | `text-red-700` |

(Em formulários como `/usuario/criar`, o tom é mais sóbrio/claro — ver seção 7.)

---

## 4. Tipografia

| Uso | Família | Pesos | Característica |
|---|---|---|---|
| **Toda a UI** | [Inter](https://fonts.google.com/specimen/Inter) | 400, 500, 600, 700, 800, 900 | Sans-serif neutra |

**Não usamos** font display de fantasia (Russo One, etc.). MetroUI é sans-serif clean em pesos pesados.

### Hierarquia
| Elemento | Classes |
|---|---|
| Logo da marca (GAMEDB) | `text-2xl sm:text-3xl font-black tracking-widest` |
| H1 hero (marketing) | `text-5xl sm:text-6xl md:text-7xl font-black tracking-tight uppercase leading-[0.95]` |
| H2 section | `text-xl sm:text-2xl font-black tracking-widest uppercase` + barra violeta à esquerda |
| H3 card | `text-base font-bold leading-snug` (ou `text-3xl font-black uppercase` em tiles grandes) |
| Labels/CTAs | `text-xs font-black tracking-widest uppercase` |
| Corpo | `text-sm` ou `text-base text-white/60` |
| Texto sutil/data | `text-[10px] text-white/40` |

---

## 5. Componentes

### 5.1 Botão primário (CTA)
```html
<a class="px-8 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs
          hover:bg-[#8674B8] transition">
    Texto
</a>
```
- Retangular, sem borda, sem sombra
- Fundo violeta sólido, texto **preto** (contraste forte)
- Hover: violeta um pouco mais claro

### 5.2 Botão secundário (outlined)
```html
<a class="px-8 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs
          hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
    Texto
</a>
```
- Borda fina branca translúcida; fundo transparente
- Hover: borda e texto viram violeta

### 5.3 Tile (card de vitrine)
```html
<article class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer">
    <!-- thumbnail/imagem -->
    <div class="aspect-video bg-[#11101A] border-b border-white/5"></div>
    <!-- conteúdo -->
    <div class="p-5">
        <span class="badge-acento">EVENT</span>
        <h3 class="text-base font-bold">Título</h3>
        <div class="pt-4 border-t border-white/10 flex justify-between">
            <span class="text-[10px] font-black uppercase tracking-widest">READ MORE</span>
            <span class="text-[10px] text-white/40">data</span>
        </div>
    </div>
</article>
```
- Sem rounded, sem sombra
- Hover: muda o fundo (um clique mais claro)
- Separadores internos: `border-white/10`
- Cursor pointer porque o tile inteiro é clicável

### 5.4 Badge (etiqueta de categoria)
```html
<span class="inline-block px-2 py-0.5 text-[10px] font-black tracking-widest uppercase
             bg-[#6B5B9E] text-black">
    EVENT
</span>
```
Retangular, fundo violeta sólido, texto preto. **Não usar pill (`rounded-full`).**

### 5.5 Section heading
```html
<h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase
           border-l-4 border-[#6B5B9E] pl-4">
    LATEST NEWS
</h2>
```
A **barra vertical violeta de 4px** à esquerda é a assinatura dos títulos de seção — vem direto da inspiração NIS.

### 5.6 Separadores / divisórias
- Entre seções principais: `border-t border-white/10`
- Internas a tiles: `border-t border-white/10` ou `border-white/5`
- Nunca usar `<hr>` estilizado complexo

### 5.7 Mascote (Espurr no footer)
```html
<div class="w-12 h-12 bg-[#C6C2D9] opacity-60 hover:opacity-100 transition"
     style="-webkit-mask: url('{{ asset('misc/espurr.svg') }}') center/contain no-repeat;
                    mask: url('{{ asset('misc/espurr.svg') }}') center/contain no-repeat;"
     role="img" aria-label="Espurr — mascote"></div>
```
- O Espurr **não** entra como `<img>`. O SVG original é preto chapado e fica com péssimo contraste no dark.
- A silhueta é renderizada via **CSS `mask`** sobre um `<div>` colorido com a cor do corpo do Espurr (`#C6C2D9`). Isso permite tingir o mascote sem editar o SVG.
- Sempre incluir tanto `-webkit-mask` quanto `mask` (Safari ainda exige o prefixo).
- Tamanho padrão: **48px** (`w-12 h-12`). Opacidade base 60%, vira 100% no hover — está no footer, é "guardiãozinho", não banner.
- Aparece **só no footer** — não usar no hero, em forms, em headers, em estados vazios. Presença sutil é regra.

| Item | Regra |
|---|---|
| Largura máxima do conteúdo | `max-w-[1600px]` (página vitrine) |
| Padding lateral | `px-6 sm:px-12` |
| Grid de tiles | `grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1` (gap **pequeno**, tiles quase se tocam) |
| Navbar | Logo **centralizado**, links nas pontas, borda inferior `border-b border-white/10` |
| Footer | Borda superior `border-t border-white/10`, texto micro em `text-[10px] uppercase tracking-widest text-white/40` |

---

## 7. Páginas — identidade monolítica

**Todas as páginas seguem o mesmo tom dark MetroUI**, sem exceção. O que varia entre páginas é **layout e densidade de informação**, não cor/tom/peso visual.

| Página | Foco do layout |
|---|---|
| `/` (welcome) | Vitrine — tiles grandes, hero, latest news, featured titles |
| `/usuario/criar` e formulários | Card único centralizado, foco no fluxo de entrada |
| Telas internas/admin (futuras) | Densidade maior, tabelas, painéis — mesmo tom dark, menos respiro |

### Padrões compartilhados em todas as páginas
- Topbar com logo `GAMEDB` à esquerda + ação contextual à direita (Voltar, Sair, etc.)
- Heading principal com **barra violeta vertical à esquerda** (`border-l-4 border-[#6B5B9E] pl-4`)
- Footer com microcopy uppercase **e silhueta lavanda do Espurr à esquerda do copyright** (ver 5.7)
- Card / superfície interna: `bg-[#1C1B26] border border-white/10`, **sem rounded**
- Inputs: fundo `#11101A`, borda branca/10, foco com borda violeta

> Quando uma tela nova for criada, **comece dark**. Se houver tentação real de fugir disso (ex: print-friendly de relatório), abra um aditivo neste documento e justifique — não decida sozinho.

---

## 8. Implementação atual e dívida técnica

### O que está sendo usado
- **Tailwind CSS** via [Play CDN](https://cdn.tailwindcss.com) (script tag no `<head>`)
- **Google Fonts** via `<link>` no `<head>`
- **jQuery** via CDN nas páginas que precisam de AJAX

### Dívida — alinhar com o `guidelines.md`
O `guidelines.md` da empresa-modelo indica que o frontend é compilado via **Vite** (`@vite()`), e o `package.json` deste projeto já tem `@tailwindcss/vite`, `tailwindcss ^4.0.0` e `jquery ^4.0.0` instalados. Hoje estamos usando os CDNs como atalho de prototipagem; o **caminho fiel ao padrão** é:

1. Configurar o plugin `@tailwindcss/vite` em `vite.config.js`
2. Criar um arquivo CSS de entrada (`resources/css/app.css`) com `@import "tailwindcss";` (sintaxe v4)
3. Substituir os `<script src="https://cdn..."></script>` por `@vite([...])` no blade
4. Importar `jquery` do `node_modules` no entry JS

Enquanto a migração não acontece, **note a inconsistência**: o CDN entrega Tailwind **v3**, mas o `package.json` declara **v4**. As IDEs (com o plugin v4) podem reclamar de classes em sintaxe v3 (`max-w-[1600px]`, `bg-gradient-to-br`) sugerindo a sintaxe v4 (`max-w-400`, `bg-linear-to-br`). **Não trocar pelas v4** enquanto o CDN for v3 — quebra o visual.

---

## 9. O que este documento NÃO governa

❌ Estrutura de pastas e namespaces PHP
❌ Padrão Controller / Service / Repository / DTO / Resource
❌ Validação (formato, unicidade, regras de negócio)
❌ Transações, paginação, tratamento de exceções
❌ Convenções de rota, permissões, nomenclatura PHP (snake_case com prefixo, etc.)
❌ Boas práticas de PHP 8 / Laravel
❌ Migrations e regras do banco

**Tudo isso → [`guidelines.md`](guidelines.md).**

---

**Mantido por:** projeto pessoal de estudo — Game Database
**Inspiração visual:** [nisamerica.com](https://nisamerica.com) — estética MetroUI
**Última revisão:** 2026-05-28
