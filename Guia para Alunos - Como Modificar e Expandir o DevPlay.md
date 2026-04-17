# 📚 Guia para Alunos - Como Modificar e Expandir o DevPlay

Este guia foi criado especialmente para vocês, alunos do curso de Desenvolvimento de Sistemas, para que possam entender, modificar e expandir a plataforma DevPlay.

## 🎯 Objetivo deste Guia

Ajudar vocês a:
- Entender como cada parte do código funciona
- Fazer modificações seguras no código
- Adicionar novos recursos
- Criar seus próprios jogos
- Aprender boas práticas de programação

## 📁 Estrutura dos Arquivos

```
devplay/
├── index.html              ← Página principal (ESTRUTURA)
├── css/style.css           ← Estilos visuais (APARÊNCIA)
├── js/script.js            ← Funcionalidades (COMPORTAMENTO)
├── games/                  ← Pasta dos jogos
│   └── quiz-html/          ← Exemplo de jogo
│       └── index.html
├── images/                 ← Imagens e placeholders
│   └── game-placeholders/
├── README.md               ← Documentação geral
└── GUIA_PARA_ALUNOS.md    ← Este arquivo
```

## ♿ Recursos de Acessibilidade Implementados

O DevPlay foi desenvolvido seguindo as melhores práticas de acessibilidade web (WCAG). Aqui estão os principais recursos:

### 🔧 Tecnologias Assistivas Integradas
- **VLibras:** Widget para tradução automática em Libras
- **UserWay:** Ferramentas de acessibilidade (zoom, contraste, etc.)
- **Navegação por teclado:** Suporte completo para usuários que não usam mouse
- **Leitores de tela:** Compatível com NVDA, JAWS, VoiceOver

### 🎯 Atributos ARIA Implementados
- `role="banner"`, `role="navigation"`, `role="main"`
- `aria-label` em todos os botões e elementos interativos
- `aria-labelledby` conectando seções com títulos
- `aria-expanded` para controle do menu mobile
- `aria-live` para notificações dinâmicas

## 🔧 Como Fazer Modificações Básicas

### 1. Mudando Cores da Plataforma

**Arquivo:** `css/style.css` (linhas 15-35)

```css
/* Encontre esta seção no CSS: */
:root {
    --primary: #6366f1;        /* COR PRINCIPAL - mude aqui! */
    --secondary: #8b5cf6;      /* COR SECUNDÁRIA - mude aqui! */
    --accent: #06b6d4;         /* COR DE DESTAQUE - mude aqui! */
    /* ... outras cores ... */
}
```

**Como fazer:**
1. Abra o arquivo `css/style.css`
2. Procure por `:root {` (linha 15)
3. Mude os valores das cores (use sites como colorhunt.co para inspiração)
4. Salve e recarregue a página

**Exemplo de mudança:**
```css
--primary: #ff6b6b;        /* Vermelho */
--secondary: #4ecdc4;      /* Verde água */
--accent: #45b7d1;         /* Azul claro */
```

### 2. Adicionando um Novo Jogo Externo

**Arquivo:** `index.html` (seção de jogos)

**Passos:**
1. Copie um card de jogo existente
2. Cole após o último card
3. Modifique as informações
4. **IMPORTANTE:** Sempre inclua atributos de acessibilidade

**Exemplo:**
```html
<!-- NOVO CARD - Cole este código -->
<article class="game-card" role="listitem" tabindex="0">
    <div class="game-image">
        <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'><rect width='300' height='200' fill='%23ff6b6b'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='16' font-family='Arial'>MEU JOGO</text></svg>" 
             alt="Meu Jogo - Descrição detalhada do que o jogo ensina" 
             loading="lazy">
    </div>
    <div class="game-content">
        <h3 class="game-title">Nome do Meu Jogo</h3>
        <p class="game-description">Descrição do que o jogo ensina</p>
        <button class="game-button" 
                data-type="external" 
                data-url="https://meusite.com/jogo"
                aria-label="Jogar Meu Jogo - abre em nova aba">
            Jogar Agora
        </button>
    </div>
</article>
```

**⚠️ Checklist de Acessibilidade para Novos Jogos:**
- ✅ Inclua `role="listitem"` e `tabindex="0"` no card
- ✅ Use `alt` descritivo nas imagens
- ✅ Adicione `aria-label` detalhado nos botões
- ✅ Mantenha títulos (`h3`) descritivos

### 3. Mudando Textos da Página

**Arquivo:** `index.html`

**Principais textos para modificar:**

```html
<!-- Título principal (linha 45) -->
<h1 class="site-title">DevPlay</h1>
<!-- Mude para: -->
<h1 class="site-title">Minha Plataforma</h1>

<!-- Subtítulo (linha 46) -->
<span class="site-subtitle">Plataforma de Jogos para Treinar Desenvolvimento</span>
<!-- Mude para: -->
<span class="site-subtitle">Minha descrição personalizada</span>

<!-- Título da seção hero (linha 60) -->
<h2 id="hero-title" class="hero-title">Aprenda Programação Jogando</h2>
<!-- Mude para: -->
<h2 id="hero-title" class="hero-title">Seu novo título aqui</h2>
```

## 🎮 Como Criar um Novo Jogo Interno

### Passo 1: Criar a Pasta do Jogo

1. Vá na pasta `games/`
2. Crie uma nova pasta com o nome do seu jogo (ex: `meu-quiz-css`)
3. Dentro dela, crie um arquivo `index.html`

### Passo 2: Estrutura Básica do Jogo (Com Acessibilidade)

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Jogo - DevPlay</title>
    <style>
        /* Seus estilos aqui */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        
        .game-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
        }
        
        /* Estilos para acessibilidade */
        button:focus {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
        }
        
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body>
    <main role="main" aria-labelledby="game-title">
        <div class="game-container">
            <h1 id="game-title">🎯 Meu Jogo Incrível</h1>
            <p>Conteúdo do seu jogo aqui!</p>
            
            <!-- Botão para voltar ao DevPlay com acessibilidade -->
            <button onclick="window.parent.postMessage('closeGame', '*')"
                    aria-label="Fechar jogo e voltar ao DevPlay">
                Voltar ao DevPlay
            </button>
        </div>
    </main>

    <script>
        // Sua lógica do jogo aqui
        console.log('Meu jogo carregou!');
        
        // Foco automático no título para leitores de tela
        document.getElementById('game-title').focus();
    </script>
</body>
</html>
```

**🔍 Novos Elementos de Acessibilidade Explicados:**
- `role="main"` - Identifica o conteúdo principal
- `aria-labelledby` - Conecta a seção com seu título
- `aria-label` - Descrição detalhada para botões
- `.sr-only` - Classe para textos apenas para leitores de tela
- `outline` no `:focus` - Indicador visual de foco

### Passo 3: Adicionar o Jogo na Página Principal

No arquivo `index.html`, adicione um novo card com acessibilidade completa:

```html
<article class="game-card" role="listitem" tabindex="0">
    <div class="game-image">
        <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'><rect width='300' height='200' fill='%23ff6b6b'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='16' font-family='Arial'>MEU JOGO</text></svg>" 
             alt="Meu Jogo - Descrição detalhada do conteúdo educativo" 
             loading="lazy">
    </div>
    <div class="game-content">
        <h3 class="game-title">Meu Jogo Incrível</h3>
        <p class="game-description">Descrição do meu jogo</p>
        <button class="game-button" 
                data-type="internal" 
                data-url="games/meu-quiz-css/index.html"
                aria-label="Jogar Meu Jogo Incrível - abre em modal">
            Jogar Agora
        </button>
    </div>
</article>
```

## 🔔 Sistema de Notificações

O DevPlay agora inclui um sistema de notificações integrado ao menu de navegação.

### Como Funciona
- **Localização:** Botão 🔔 no menu de navegação
- **Badge:** Mostra número de notificações não lidas
- **Acessibilidade:** Navegável por teclado e compatível com leitores de tela

### Adicionando Notificações Personalizadas

**Arquivo:** `js/script.js`

```javascript
// Para mostrar uma notificação:
this.showToast('Sua mensagem aqui!', 'success');

// Tipos disponíveis:
// 'success' - ✅ (verde)
// 'error' - ❌ (vermelho) 
// 'warning' - ⚠️ (amarelo)
// 'info' - ℹ️ (azul)
```

### Personalizando o Sistema de Notificações

```javascript
// Adicione novos tipos de notificação na função getToastIcon:
getToastIcon(type) {
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️',
        // SEUS NOVOS TIPOS:
        celebration: '🎉',
        rocket: '🚀',
        trophy: '🏆'
    };
    return icons[type] || icons.info;
}
```

## 🎨 Personalizações Avançadas

### 1. Mudando o Layout dos Cards

**Arquivo:** `css/style.css` (linha 400)

```css
/* Para mudar quantas colunas aparecem: */
@media (min-width: 1024px) {
    .games-grid {
        grid-template-columns: repeat(3, 1fr); /* 3 colunas */
        /* Mude para 2 colunas: */
        grid-template-columns: repeat(2, 1fr);
        /* Ou 5 colunas: */
        grid-template-columns: repeat(5, 1fr);
    }
}
```

### 2. Adicionando Animações Personalizadas

**Arquivo:** `css/style.css` (final do arquivo)

```css
/* Adicione no final do arquivo CSS: */
.minha-animacao {
    animation: meuEfeito 2s ease-in-out infinite;
}

@keyframes meuEfeito {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
```

### 3. Navegação por Teclado

O DevPlay deve possuir navegação completa por teclado para acessibilidade:

**Controles Principais:**
- **Tab/Shift+Tab:** Navega entre elementos
- **Enter/Space:** Ativa botões e links
- **Escape:** Fecha modal de jogos
- **Setas (←→↑↓):** Navega entre cards de jogos
- **Home/End:** Vai para primeiro/último jogo

**Atalhos Rápidos (Alt + tecla):**
- **Alt + 1:** Foco no logo
- **Alt + 2:** Foco em "Jogos"  
- **Alt + 3:** Foco em "Sobre"
- **Alt + N:** Foco em notificações

### 4. Widgets de Acessibilidade Externos

**VLibras (Tradução em Libras):**
- Aparece automaticamente no canto inferior direito
- Traduz todo o conteúdo da página para Libras
- Não requer configuração adicional

**UserWay (Ferramentas de Acessibilidade):**
- Widget com recursos como zoom, contraste, navegação
- Ativado automaticamente
- Personalizável através do painel UserWay no site (link no código)

## 🐛 Como Debugar (Encontrar Erros)

### 1. Usando o Console do Navegador

1. Pressione `F12` no navegador
2. Vá na aba "Console"
3. Procure por mensagens de erro (em vermelho)

### 2. Erros Comuns e Soluções

**Erro:** "Cannot read property of null"
**Solução:** Verifique se o ID do elemento HTML está correto

```javascript
// ERRADO:
const elemento = document.getElementById('meuID'); // Se não existir, será null
elemento.textContent = 'texto'; // ERRO!

// CORRETO:
const elemento = document.getElementById('meuID');
if (elemento) {
    elemento.textContent = 'texto';
}
```

**Erro:** CSS não está funcionando
**Solução:** Verifique se:
- O nome da classe está correto
- Não há erros de sintaxe (ponto e vírgula, chaves)
- O arquivo CSS está sendo carregado

**Erro:** Acessibilidade não funciona
**Solução:** Verifique se:
- Atributos ARIA estão corretos (`aria-label`, `role`)
- IDs estão únicos e conectados corretamente
- Elementos focáveis têm `tabindex` apropriado

### 3. Testando Modificações

1. Faça uma mudança pequena por vez
2. Teste no navegador
3. **Teste com acessibilidade:** Use Tab para navegar, teste com leitor de tela
4. Se quebrou, desfaça a mudança
5. Tente novamente de forma diferente

### 4. Testando Acessibilidade

**Ferramentas para Testar:**
- **Navegação por teclado:** Use apenas Tab, Enter, Escape, setas
- **Leitor de tela:** NVDA (gratuito) ou VoiceOver (Mac)
- **Lighthouse:** F12 > Lighthouse > Accessibility
- **Contrast checker:** WebAIM Contrast Checker online

## 📱 Tornando Responsivo

### Conceitos Básicos

```css
/* Mobile First - comece sempre pelo celular */
.meu-elemento {
    width: 100%;        /* Celular */
    padding: 10px;
}

/* Tablet */
@media (min-width: 768px) {
    .meu-elemento {
        width: 50%;     /* Tablet */
        padding: 20px;
    }
}

/* Desktop */
@media (min-width: 1024px) {
    .meu-elemento {
        width: 33.33%;  /* Desktop */
        padding: 30px;
    }
}
```

## 🚀 Ideias de Projetos para Praticar

### Nível Iniciante
1. **Mudar todas as cores** da plataforma
2. **Adicionar 3 jogos externos** novos
3. **Modificar todos os textos** para sua escola/turma
4. **Criar um jogo simples** de "Verdadeiro ou Falso"
5. **Personalizar notificações** com novos tipos e mensagens

### Nível Intermediário
1. **Quiz de CSS** (copie o quiz HTML e mude as perguntas)
2. **Jogo da memória** com conceitos de programação
3. **Calculadora simples** como jogo interno
4. **Sistema de ranking** (salvar pontuações no localStorage)
5. **Melhorar acessibilidade** de jogos existentes

### Nível Avançado
1. **Jogo de digitação** para treinar código
2. **Simulador de Git** (comandos básicos)
3. **Editor de código simples** com syntax highlighting
4. **Sistema de usuários** com login
5. **Dashboard de acessibilidade** com métricas e relatórios

## ♿ Guia de Boas Práticas de Acessibilidade

### Sempre Inclua em Novos Elementos:

**Para Botões:**
```html
<button aria-label="Descrição clara da ação" tabindex="0">
    Texto do Botão
</button>
```

**Para Imagens:**
```html
<img src="caminho.jpg" alt="Descrição detalhada da imagem" loading="lazy">
```

**Para Seções:**
```html
<section aria-labelledby="titulo-secao">
    <h2 id="titulo-secao">Título da Seção</h2>
</section>
```

**Para Elementos Interativos:**
```html
<div role="button" tabindex="0" aria-label="Descrição da ação">
    Conteúdo clicável
</div>
```

## 🔗 Recursos Úteis

### Sites para Aprender
- **MDN Web Docs:** developer.mozilla.org (documentação oficial)
- **W3Schools:** w3schools.com (tutoriais básicos)
- **CSS Tricks:** css-tricks.com (dicas de CSS)

### Ferramentas
- **VS Code:** Editor de código gratuito
- **Chrome DevTools:** F12 no navegador
- **Colorhunt:** colorhunt.co (paletas de cores)
- **Google Fonts:** fonts.google.com (fontes gratuitas)

### Ferramentas de Acessibilidade
- **NVDA:** Leitor de tela gratuito (nvaccess.org)
- **WebAIM:** Verificador de contraste (webaim.org/resources/contrastchecker)
- **axe DevTools:** Extensão para Chrome/Firefox
- **Lighthouse:** Auditoria de acessibilidade integrada no Chrome

### Geradores Online
- **CSS Gradient:** cssgradient.io
- **Box Shadow:** cssmatic.com/box-shadow
- **Border Radius:** border-radius.com

## ❓ FAQ - Perguntas Frequentes

**P: Como adiciono uma fonte diferente?**
R: No CSS, adicione no início:
```css
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');

body {
    font-family: 'Roboto', sans-serif;
}
```

**P: Como faço o site funcionar no celular?**
R: O site já é responsivo! Mas sempre teste em diferentes tamanhos usando F12 > Toggle Device Toolbar.

**P: Posso usar jQuery ou outras bibliotecas?**
R: Pode, mas recomendamos aprender JavaScript puro primeiro. Para adicionar jQuery:
```html
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
```

**P: Como hospedo meu site?**
R: Use GitHub Pages (gratuito):
1. Crie conta no GitHub
2. Crie repositório público
3. Faça upload dos arquivos
4. Ative GitHub Pages nas configurações

**P: O que fazer se quebrar tudo?**
R: Não se desespere! Sempre mantenha uma cópia de backup. Use Ctrl+Z para desfazer ou baixe o código original novamente.

**P: Como funciona o sistema de notificações?**
R: O botão 🔔 no menu mostra notificações. Use `this.showToast('mensagem', 'tipo')` no JavaScript para criar novas notificações.

**P: Como desabilitar os widgets de acessibilidade?**
R: Remova as linhas do VLibras e UserWay no `<head>` do `index.html`, mas recomendamos manter para inclusão.

**P: Como testar se meu código é acessível?**
R: Use Tab para navegar, teste com NVDA (leitor de tela), e execute Lighthouse no F12 do Chrome.

## 🎓 Próximos Passos

1. **Domine o básico:** HTML, CSS, JavaScript
2. **Aprenda frameworks:** React, Vue.js
3. **Backend:** Node.js, Python, PHP
4. **Banco de dados:** MySQL, MongoDB
5. **Versionamento:** Git e GitHub
6. **Deploy:** Netlify, Vercel, Heroku

## 💡 Dicas Finais

1. **Pratique todos os dias** - mesmo que seja 15 minutos
2. **Não tenha medo de errar** - erros são parte do aprendizado
3. **Comente seu código** - você vai agradecer depois
4. **Teste em diferentes navegadores** - Chrome, Firefox, Safari
5. **Teste acessibilidade sempre** - use Tab, leitores de tela, Lighthouse
6. **Peça ajuda** - comunidade de programadores é muito acolhedora
7. **Mantenha-se atualizado** - tecnologia muda rápido
8. **Construa projetos pessoais** - portfolio é importante
9. **Pense em inclusão** - desenvolva para todos os usuários

## 🤝 Contribuindo

Se vocês criarem jogos legais ou melhorias, compartilhem com a turma! Programação é colaborativa.

**Lembrem-se:** Todo programador experiente já foi iniciante. O importante é não desistir e continuar praticando!

---

**Feito com ❤️ para os alunos do curso de Desenvolvimento de Sistemas**

*"O único jeito de fazer um ótimo trabalho é amar o que você faz." - Steve Jobs*

