# 🎮 DevPlay - Plataforma de Jogos para Treinar Desenvolvimento

Uma plataforma educativa moderna e responsiva para aprender programação através de jogos interativos. Desenvolvida com HTML, CSS e JavaScript puro, sem dependências externas.

## ✨ Características

- **Design Responsivo**: Otimizado para celular, tablet e desktop
- **Jogos Internos**: Sistema de modal fullscreen para jogos hospedados localmente
- **Jogos Externos**: Verificação automática de disponibilidade com fallbacks
- **Acessibilidade**: Navegação por teclado, ARIA labels e focus management
- **Notificações**: Sistema de toast para feedback do usuário
- **Performance**: Carregamento rápido e animações suaves

## 🚀 Como Usar

### Instalação Simples

1. Faça o download ou clone este repositório
2. Abra o arquivo `index.html` em qualquer navegador moderno
3. Pronto! A plataforma está funcionando

### Hospedagem no GitHub Pages

1. Faça fork deste repositório
2. Vá em Settings > Pages
3. Selecione "Deploy from a branch" > "main"
4. Sua plataforma estará disponível em `https://seuusuario.github.io/devplay`

## 🎯 Estrutura do Projeto

```
devplay/
├── index.html              # Página principal
├── css/
│   └── style.css           # Estilos responsivos
├── js/
│   └── script.js           # Funcionalidades interativas
├── images/
│   └── game-placeholders/  # Imagens dos jogos
├── games/                  # Pasta para jogos internos
│   └── quiz-html/          # Exemplo: Quiz HTML
│       └── index.html
└── README.md               # Esta documentação
```

## 🎮 Adicionando Novos Jogos

### Jogos Internos (Recomendado)

1. Crie uma pasta dentro de `games/` com o nome do seu jogo:
   ```
   games/meu-jogo/
   ├── index.html
   ├── style.css (opcional)
   └── script.js (opcional)
   ```

2. No arquivo `index.html` principal, adicione um novo card:
   ```html
   <article class="game-card" role="listitem">
       <div class="game-image">
           <img src="caminho/para/imagem.jpg" alt="Descrição do jogo">
       </div>
       <div class="game-content">
           <h3 class="game-title">Nome do Jogo</h3>
           <p class="game-description">Descrição do que o jogo ensina</p>
           <button class="game-button" 
                   data-type="internal" 
                   data-url="games/meu-jogo/index.html">
               Jogar Agora
           </button>
       </div>
   </article>
   ```

### Jogos Externos

Para adicionar links para jogos externos, use `data-type="external"`:

```html
<button class="game-button" 
        data-type="external" 
        data-url="https://exemplo.com/jogo">
    Acessar Jogo
</button>
```

## 🛠️ Personalização

### Cores e Tema

As cores estão definidas como variáveis CSS no arquivo `css/style.css`:

```css
:root {
    --primary: #6366f1;
    --secondary: #8b5cf6;
    --accent: #06b6d4;
    /* ... outras cores */
}
```

### Adicionando Novos Tipos de Jogo

1. Edite o arquivo `js/script.js`
2. Adicione lógica no método `handleGameClick()`
3. Implemente tratamento específico se necessário

### Modificando Layout

O layout usa CSS Grid responsivo. Para alterar o número de colunas:

```css
.games-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
}
```

## 🎨 Recursos de Design

- **Paleta de Cores**: Tons suaves de azul e roxo
- **Tipografia**: Segoe UI (system font)
- **Animações**: Transições suaves e hover effects
- **Ícones**: Emojis para compatibilidade universal
- **Sombras**: Sistema consistente de elevação

## 📱 Responsividade

A plataforma é otimizada para:

- **Mobile**: 320px+ (design mobile-first)
- **Tablet**: 768px+
- **Desktop**: 1024px+
- **Large Desktop**: 1440px+

## ♿ Acessibilidade

- Navegação por teclado completa
- ARIA labels e roles
- Focus management no modal
- Contraste adequado de cores
- Suporte a screen readers
- Redução de movimento para usuários sensíveis

## 🔧 Funcionalidades Técnicas

### Modal System
- Fullscreen para jogos internos
- Focus trap automático
- Escape key para fechar
- Loading states

### Error Handling
- Verificação de links externos
- Fallback para jogos alternativos
- Notificações informativas
- Retry mechanisms

### Performance
- Lazy loading de imagens
- Debounced event handlers
- CSS animations otimizadas
- Minimal DOM manipulation

## 🎓 Exemplos de Jogos

### Quiz HTML (Incluído Protótipo de Demonstração)
Um quiz interativo sobre HTML básico com:
- 5 perguntas de múltipla escolha
- Feedback visual imediato
- Sistema de pontuação
- Design responsivo

### Ideias para Novos Jogos
- Quiz CSS
- Quiz JavaScript
- Jogo da Memória com conceitos de programação
- Simulador de Git
- Desafios de lógica
- Desafio de Golf com Código

## 🤝 Contribuindo

1. Faça fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/NovoJogo`)
3. Commit suas mudanças (`git commit -m 'Adiciona novo jogo'`)
4. Push para a branch (`git push origin feature/NovoJogo`)
5. Abra um Pull Request

## 📄 Licença

Este projeto é open source e está disponível sob a [MIT License](LICENSE).

## 👥 Créditos

Desenvolvido por alunos do curso de Desenvolvimento de Sistemas.

### Tecnologias Utilizadas
- HTML5 semântico
- CSS3 com Grid e Flexbox
- JavaScript ES6+
- Design responsivo
- Acessibilidade web

## 🆘 Suporte

Se encontrar problemas ou tiver sugestões:

1. Verifique se está usando um navegador moderno
2. Abra as ferramentas de desenvolvedor (F12) para ver erros
3. Crie uma issue no GitHub com detalhes do problema

## 🔮 Roadmap

- [ ] Sistema de usuários e progresso
- [ ] Mais jogos educativos
- [ ] Integração com APIs educacionais
- [ ] Sistema de conquistas
- [ ] PWA (Progressive Web App)
- [ ] Multiplayer básico
- [ ] Logotipo e Linguagem Visual

---

**DevPlay** - Aprendendo programação de forma divertida! 🚀

